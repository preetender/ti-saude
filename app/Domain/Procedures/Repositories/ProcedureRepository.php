<?php

namespace App\Domain\Procedures\Repositories;

use App\Core\Repository;
use App\Domain\Procedures\Models\Procedure;
use App\Http\Requests\Procedure\StoreRequest;
use App\Http\Requests\Procedure\UpdateRequest;
use App\Http\Resources\ProcedureResource;

class ProcedureRepository extends Repository
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
        return Procedure::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return ProcedureResource::class;
    }
}
