<?php

namespace App\Domain\Plans\Models;

use App\Core\Concerns\HasCodeable;
use App\Core\Concerns\HasSearch;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    use HasFactory, HasSearch, HasCodeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'code', 'phone'];

    /**
     * Colunas acessiveis para busca.
     *
     * @var array<string>
     */
    protected $searchable = ['description'];

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
    public function patients()
    {
        return $this
            ->belongsToMany(Patient::class)
            ->withPivot('contract_number')
            ->using(PatientPlan::class);
    }
}
