<?php

namespace App\Domain\Doctors\Repositories;

use App\Core\Repository;
use App\Domain\Doctors\Models\Doctor;
use App\Http\Requests\Doctor\StoreRequest;
use App\Http\Requests\Doctor\UpdateRequest;
use App\Http\Resources\DoctorResource;

class DoctorRepository extends Repository
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
        return Doctor::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return DoctorResource::class;
    }
}
