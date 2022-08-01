<?php

namespace App\Domain\Specialities\Models;

use App\Core\Concerns\HasCodeable;
use App\Core\Concerns\HasSearch;
use App\Domain\Doctors\Models\Doctor;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speciality extends Model
{
    use HasFactory, HasSearch, HasCodeable;

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
     * @param  Builder  $query
     * @param  mixed  $input
     * @param  mixed  $column
     * @return void
     */
    public function applyFilters(Builder $query, $input, $column): void
    {
        //
    }

    /**
     * @return BelongsToMany|Doctor[]
     */
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }
}
