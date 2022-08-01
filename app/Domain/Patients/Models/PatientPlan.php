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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
