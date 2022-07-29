<?php

namespace App\Domain\Patients\Models;

use App\Core\Concerns\HasSearch;
use App\Domain\Plans\Models\Plan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    use HasFactory, HasSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'birth_date'];

    /**
     * Colunas acessiveis para busca.
     *
     * @var array<string>
     */
    protected $searchable = ['name'];

    /**
     * Executa busca em colunas de forma agregada.
     *
     * @param Builder $query
     * @param mixed $input
     * @param mixed $column
     * @return void
     */
    public function applyFilters(Builder $query, $input, $column): void
    {
        //
    }

    /**
     * @return BelongsToMany
     */
    public function plans()
    {
        return $this
            ->belongsToMany(Plan::class)
            ->withPivot('contract_number')
            ->using(PatientPlan::class);
    }
}
