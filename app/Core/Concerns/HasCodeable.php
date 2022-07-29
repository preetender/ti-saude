<?php

namespace App\Core\Concerns;

use App\Core\Supports\CodeSupport;
use Illuminate\Database\Eloquent\Model;

trait HasCodeable
{
    /**
     * Coluna que representa o campo 'codigo'.
     *
     * @var string
     */
    protected string $code_column = 'code';

    /**
     * @return void
     */
    public static function bootHasCodeable(): void
    {
        static::creating(function (Model $model) {
            $column = $model->{$model->code_column};

            $model->{$model->code_column} = empty($column) ?
                CodeSupport::factory($model->getTable())->random()
                : $column;
        });
    }
}
