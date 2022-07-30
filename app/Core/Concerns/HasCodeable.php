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
        static::saving(function (Model $model) {
            $value = $model->{$model->code_column} ?? $model->getOriginal('code');

            $model->{$model->code_column} = ! $value ? CodeSupport::factory($model->getTable())->random() : $value;
        });
    }
}
