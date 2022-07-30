<?php

namespace App\Core;

use App\Domain\Users\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Preetender\Finder\Interceptor;

enum EventType: string
{
    case BEFORE = 'before';
    case DURING = 'during';
    case AFTER = 'after';
}

enum Action: string
{
    case UPDATE = 'update';
    case CREATE = 'create';
    case DELETE = 'delete';

    case RESTORE = 'restore';
}

abstract class Repository
{
    /**
     * @var array<string>
     */
    protected array $validations = [];

    /**
     * @var bool
     */
    protected bool $penging = false;

    /**
     * @var array
     */
    protected array $events = [
        'before' => [],
        'during' => [],
        'after' => [],
    ];

    /**
     * @param  Request  $request
     * @return void
     */
    public function __construct(protected Request $request)
    {
        $this->registerEvents();

        method_exists($this, 'boot') && call_user_func([$this, 'boot']);
    }

    /**
     * @return mixed
     */
    abstract protected function model();

    /**
     * @return static
     */
    public static function make()
    {
        return new static(app('request'));
    }

    /**
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function getAlias()
    {
        $self = get_called_class();

        preg_match("#(\w+)Repository#", $self, $matches);

        return Str::of($matches[1])
            ->trim()
            ->slug()
            ->toString();
    }

    /**
     * Obtem eventos iniciado no repositorio e registra.
     *
     * @return void
     */
    public function registerEvents()
    {
        $keys = [
            'beforeUpdate',
            'afterUpdate',
            'beforeCreate',
            'afterCreate',
            'beforeDelete',
            'afterDelete',
        ];

        $methods = array_filter(get_class_methods($this), fn ($h) => in_array($h, $keys));

        foreach ($methods as $method) {
            [$event, $action] = Str::of($method)->kebab()->explode('-');

            $this->events[$event][$action] = $this->{$method}();
        }
    }

    /**Summary of resource
     * @return string
     */
    public function resource()
    {
        return JsonResource::class;
    }

    /**
     * @param  mixed  $data
     * @param  bool  $collection
     * @return mixed
     */
    public function resolveResource(mixed $data, bool $collection = false)
    {
        $query = $this->getModel();

        $data = match (gettype($data)) {
            'string',
            'int' => $query->findOrFail($data),
            default => $data
        };

        if ($collection) {
            return $this->resource()::collection($data);
        }

        return new ($this->resource())($data);
    }

    /**
     * @param  bool  $newInstance
     * @return mixed
     */
    public function interceptor(bool $newInstance = false, callable $callable = null)
    {
        $resolve = is_callable($callable) ? $callable($this->getModel()) : $this->getModel();

        return (new Interceptor($this->getRequest()))->watch($resolve, $newInstance);
    }

    /**
     * Sincroniza lista de items por relacionamento.
     *
     * @param  Model  $model
     * @param  string  $relation
     * @param  mixed  $rows
     * @param  callable  $map
     * @return array
     */
    public function syncCollection(Model $model, string $relation, mixed $rows = [], callable $map)
    {
        $old = $model->{$relation};
        $new = [];

        $rows = $rows instanceof Collection ? $rows : collect($rows);

        if ($rows->isEmpty()) {
            $model->{$relation}()->forceDelete();

            return compact('old', 'new');
        }

        $sync = [];

        foreach ($rows as $row) {
            $value = $map($row);

            $id = $value['id'] ?? null;

            $created = $model->{$relation}()->updateOrCreate([
                'id' => $id,
            ], $value);

            if ($created->wasRecentlyCreated) {
                $new[] = $created;
            }

            $sync[] = $created->id;
        }

        if ($old->isNotEmpty()) {
            $deleted = $old
                ->pluck('id')
                ->diff($sync)
                ->toArray();

            !empty($deleted) && $model->{$relation}()->whereIn('id', $deleted)->forceDelete();
        }

        return compact('old', 'new');
    }

    /**
     * Atualizar por id.
     *
     * @param  int  $id
     * @param  array  $data
     * @return array
     */
    public function updateById(int $id, array $data = [])
    {
        $this->request->merge($data);

        $this->resolveClassValidator('update');

        return DB::transaction(function () use ($id, $data) {
            $data = empty($data) ? $this->getPayload(false) : $data;

            $model = $this->getModel()->findOrFail($id);

            $model = $this->resolveEvent($model, Action::UPDATE, EventType::BEFORE);
            $model->fill($data);
            $model->save();

            $this->resolveEvent($model, Action::UPDATE, EventType::AFTER);

            $changes = $model->getChanges();

            $model->refresh();

            return [
                $model,
                $changes,
            ];
        });
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create()
    {
        $this->resolveClassValidator(__FUNCTION__);

        return DB::transaction(function () {
            $model = $this->getModel()->newInstance();

            $this->resolveEvent($model, Action::CREATE, EventType::BEFORE);

            $model->fill($this->getPayload());
            $model->save();

            $this->resolveEvent($model, Action::CREATE, EventType::AFTER);

            $model->refresh();

            return $this->resolveResource($model);
        });
    }

    /**
     * Remover registro por id.
     *
     * @param  int  $id
     * @param  bool  $force
     * @return mixed
     */
    public function deleteById(int $id, bool $force = false)
    {
        return DB::transaction(function () use ($id, $force) {
            $model = $this->getModel()
                ->newQuery()
                ->findOrFail($id);

            $this->resolveEvent($model, Action::DELETE, EventType::BEFORE);

            $force ? $model->forceDelete() : $model->delete();

            $this->resolveEvent($model, Action::DELETE, EventType::AFTER);

            return $model;
        });
    }

    /**
     * Restaura dado por id.
     *
     * @param  int  $id
     * @param  bool  $force
     * @return mixed
     */
    public function restore(mixed $id)
    {
        return DB::transaction(function () use ($id) {
            $model = $this->getModel()
                ->newQuery()
                ->findOrFail($id);

            $this->resolveEvent($model, Action::RESTORE, EventType::BEFORE);

            $model->restore();

            $this->resolveEvent($model, Action::RESTORE, EventType::AFTER);

            return $model;
        });
    }

    /**
     * Executa validação de dados.
     *
     * @param  string  $class
     * @return Repository
     */
    public function beforeValidate(FormRequest|string $class)
    {
        $this->penging = true;

        $validator = app($class);

        $validator->validateResolved();

        $this->penging = false;

        return $this;
    }

    /**
     * Resolve classe de validação.
     *
     * @param  string  $method
     * @return mixed
     */
    protected function resolveClassValidator(string $method)
    {
        preg_match('/(update|create)/', $method, $matches);

        $class = match ($matches[0]) {
            'create' => 'StoreRequest',
            'update' => 'UpdateRequest',
        };

        $validation = array_values(
            array_filter(
                $this->validations,
                fn ($value) => str_contains($value, $class)
            )
        );

        if (empty($validation)) {
            return;
        }

        $this->beforeValidate($validation[0]);
    }

    /**
     * Executa evento.
     *
     * @param  Model  $model
     * @param  Action  $action
     * @param  EventType  $event
     * @return mixed
     */
    protected function resolveEvent(Model &$model, Action $action, EventType $event)
    {
        $events = $this->events[$event->value] ?? [];

        foreach ($events as $method => $callback) {
            if ($action->value !== $method) {
                continue;
            }
            $callback($model);

            unset($this->events[$event->value][$action->value]);
        }

        return $model;
    }

    /**
     * Obtem requisição.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Obtem instancia do modelo.
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function getModel()
    {
        return app($this->model());
    }

    /**
     * Dados assesiveis do modelo.
     *
     * @return array
     */
    public function getModelFillable(): array
    {
        return app($this->model())->getFillable();
    }

    /**
     * Tratar dados de entrada.
     *
     * @return array
     */
    public function getPayload(bool $strict = false)
    {
        $input = $this->getRequest()->all();

        if (!$strict) {
            return $input;
        }

        $payload = array_filter($input);

        return $payload;
    }

    /**
     * Obtem usuario auntenticado.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->getRequest()->user();
    }

    /**
     * @return mixed
     */
    public function all(...$columns)
    {
        return $this->getModel()->all(empty($columns) ? ['*'] : $columns);
    }
}
