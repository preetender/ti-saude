<?php

namespace App\Domain\Patients\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PatientPlan extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contract_number'];
}
