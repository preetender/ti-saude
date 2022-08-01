<?php

namespace App\Core\Concerns;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

trait HasCrud
{
    /**
     * @param  Request  $request
     * @param  mixed  $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $id = $this->getRouteId($request, $id);

        method_exists($this, 'beforeShow') && $this->beforeShow($request);

        return $this->repository->resolveResource($id);
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return $this->repository->create();
    }

    /**
     * @param  Request  $request
     * @param  mixed  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $id = $this->getRouteId($request, $id);

        [$model] = $this->repository->updateById($id);

        return $this->repository->resolveResource($model);
    }

    /**
     * @param  Request  $request
     * @param  mixed  $id
     * @return Response|ResponseFactory
     *
     * @throws BindingResolutionException
     */
    public function destroy(Request $request, $id)
    {
        $id = $this->getRouteId($request, $id);

        $this->repository->deleteById($id, $request->has('force'));

        return response(null, 204);
    }

    /**
     * @param  Request  $request
     * @param  mixed  $id
     * @return mixed
     */
    public function restore(Request $request, $id)
    {
        $id = $this->getRouteId($request, $id);

        $model = $this->repository->restore($id);

        return $this->repository->resolveResource($model);
    }

    /**
     * @param  Request  $request
     * @param  mixed  $default
     * @return mixed
     */
    private function getRouteId(Request $request, mixed $default = null)
    {
        preg_match('/(\w+)Controller$/im', Str::of(get_class($this)), $matches);

        return $request->route(Str::snake($matches[1])) ?? $default;
    }
}
