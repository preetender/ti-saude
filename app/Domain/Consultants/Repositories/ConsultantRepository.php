<?php

namespace App\Domain\Consultants\Repositories;

use App\Core\Repository;
use App\Domain\Consultants\Models\Consultant;
use App\Http\Requests\Consultant\StoreRequest;
use App\Http\Requests\Consultant\UpdateRequest;
use App\Http\Resources\ConsultantResource;

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
}
