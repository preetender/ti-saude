<?php

namespace App\Http\Controllers\Api;

use App\Core\Api\HttpStatusCode;
use App\Core\Repository;
use App\Domain\Doctors\Repositories\DoctorRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends ControllersController
{
    protected $repositories = [];

    protected Repository $repository;

    public function __construct(Request $request)
    {
        $this->repositories = [
            'user' =>  fn () => UserRepository::make(),
            'doctor' => fn () => DoctorRepository::make()
        ];

        $model = Str::singular($request->route('model'));

        abort_if(!array_key_exists($model, $this->repositories), 400, 'Repositorio não foi registrado.');

        $this->repository = $this->repositories[$model]();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->repository->resolveResource(
            $this->repository->interceptor(),
            true
        );
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function show(Request $request)
    {
        return $this->repository->resolveResource(
            $request->route('id')
        );
    }

    /**
     * @return mixed
     * @throws BindingResolutionException
     */
    public function store()
    {
        return $this->repository->create();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        list($model) = $this->repository->updateById(
            $request->route('id')
        );

        return $this->repository->resolveResource($model);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request, $model, $id)
    {
        $this->repository->deleteById($id, $request->input('force', false));

        return response(null, HttpStatusCode::NO_CONTENT);
    }
}
