<?php

namespace App\Domain\Users\Repositories;

use App\Core\Repository;
use App\Domain\Users\Models\User;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;

class UserRepository extends Repository
{
    /**
     * @var array<string>
     */
    protected array $validations = [
        StoreRequest::class,
        UpdateRequest::class,
    ];

    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return UserResource::class;
    }
}
