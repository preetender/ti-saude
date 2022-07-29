<?php

namespace App\Domain\Doctors\Observers;

use App\Domain\Doctors\Models\Doctor;
use Illuminate\Contracts\Container\BindingResolutionException;

class DoctorObserver
{
    /**
     * Gerar código unico para o médico.
     *
     * @param  Doctor $model
     * @throws BindingResolutionException
     */
    public function creating(Doctor $model): void
    {
        $model->code = 0;
    }
}
