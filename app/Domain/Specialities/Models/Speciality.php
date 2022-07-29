<?php

namespace App\Domain\Specialities\Models;

use App\Core\Concerns\HasSearch;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory, HasSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code'];

    /**
     * Colunas acessiveis para busca.
     *
     * @var array<string>
     */
    protected $searchable = ['name'];

    /**
     * Executa busca em colunas de forma agregada.
     *
     * Exemplo: /api/v1/specialties?search[column]=input
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
}
