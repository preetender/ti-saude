<?php

namespace App\Domain\Procedures\Models;

use App\Core\Concerns\HasCodeable;
use App\Core\Concerns\HasSearch;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory, HasSearch, HasCodeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'value'
    ];

    /**
     * Colunas acessiveis para busca.
     *
     * @var array<string>
     */
    protected $searchable = ['name'];

    /**
     * Executa busca em colunas de forma agregada.
     *
     * @param  Builder  $query
     * @param  mixed  $input
     * @param  mixed  $column
     * @return void
     */
    public function applyFilters(Builder $query, $input, $column): void
    {
        //
    }
}
