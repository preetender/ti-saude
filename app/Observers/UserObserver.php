<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;

class UserObserver
{
    /**
     * @param  Model $model
     * @throws BindingResolutionException
     */
    public function creating(User $model): void
    {
        $model->password = app('hash')->make($model->password);
    }

    /**
     * @param  Model $model
     * @throws BindingResolutionException
     */
    public function updating(User $model): void
    {
        if ($model->isDirty('password') && $model->password) {
            $model->password = app('hash')->make($model->password);
        }
    }
}
