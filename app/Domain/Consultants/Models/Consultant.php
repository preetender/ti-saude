<?php

namespace App\Domain\Consultants\Models;

use App\Core\Concerns\HasCodeable;
use App\Core\Concerns\HasSearch;
use App\Domain\Doctors\Models\Doctor;
use App\Domain\Patients\Models\Patient;
use App\Domain\Procedures\Models\Procedure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Consultant extends Model
{
    use HasFactory, HasSearch, HasCodeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'date',
        'hour',
        'private',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'private' => 'boolean',
    ];

    /**
     * Colunas acessiveis para busca.
     *
     * @var array<string>
     */
    protected $searchable = ['code'];

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
     * Somar valor dos procedimentos.
     *
     * @return Attribute
     */
    protected function totalProcedure(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->procedures()->sum('value')
        );
    }

    /**
     * @return BelongsToMany|Procedure[]
     */
    public function procedures()
    {
        return $this->belongsToMany(Procedure::class);
    }

    /**
     * @return BelongsTo|Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * @return BelongsTo|Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
