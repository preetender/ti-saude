<?php

namespace App\Domain\Consultants\Repositories;

use App\Core\Api\Response;
use App\Core\Repository;
use App\Domain\Consultants\Models\Consultant;
use App\Http\Requests\Consultant\StoreRequest;
use App\Http\Requests\Consultant\UpdateRequest;
use App\Http\Resources\ConsultantResource;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;

class ConsultantRepository extends Repository
{
    /**
     * @var array<string>
     */
    protected array $validations = [
        StoreRequest::class,
        UpdateRequest::class,
    ];

    /**
     * @return mixed
     */
    protected function model()
    {
        return Consultant::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return ConsultantResource::class;
    }

    /**
     * @return mixed
     * @throws BindingResolutionException
     */
    public function create()
    {
        $this->resolveClassValidator(__FUNCTION__);

        return DB::transaction(function () {
            $payload = $this->getPayload();

            $consultant = $this->getModel()->newInstance();
            $consultant->fill($payload);
            $consultant->patient()->associate($payload['patient_id']);
            $consultant->doctor()->associate($payload['doctor_id']);
            $consultant->save();

            empty($payload['procedures']) ?: $consultant->procedures()->sync($payload['procedures']);

            $consultant->fresh();

            return Response::respondCreated($this->resolveResource($consultant));
        });
    }

    /**
     * Sincronizar procedimentos.
     *
     * @return Closure
     */
    public function afterUpdate()
    {
        return function (Consultant $model) {
            $payload = $this->getPayload();

            empty($payload['procedures']) ?: $model->procedures()->sync($payload['procedures']);
        };
    }
}
