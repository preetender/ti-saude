<?php

namespace App\Domain\Specialities\Repositories;

use App\Core\Repository;

use App\Domain\Specialities\Models\Speciality;
use App\Http\Requests\Speciality\StoreRequest;
use App\Http\Requests\Speciality\UpdateRequest;
use App\Http\Resources\SpecialityResource;

class SpecialityRepository extends Repository
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
        return Speciality::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return SpecialityResource::class;
    }
}
