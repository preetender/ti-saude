<?php

namespace App\Domain\Plans\Repositories;

use App\Core\Repository;

use App\Domain\Plans\Models\Plan;
use App\Http\Requests\Plan\StoreRequest;
use App\Http\Requests\Plan\UpdateRequest;
use App\Http\Resources\PlanResource;

class PlanRepository extends Repository
{
    /**
     * @var array<string>
     */
    protected array $validations = [
        StoreRequest::class,
        UpdateRequest::class
    ];

    /**
     * @return mixed
     */
    protected function model()
    {
        return Plan::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return PlanResource::class;
    }
}
