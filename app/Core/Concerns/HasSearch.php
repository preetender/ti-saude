<?php

namespace App\Core\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait HasSearch
{
    /**
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder|mixed
     */
    public function scopeSearch(Builder $query, $value)
    {
        if (! $value) return $query;

        $searchColumns = match (count($this->searchable)) {
            1 => $this->searchable[0] === '*' ? $this->getFillable() : $this->searchable,
            default => $this->getFillable()
        };

        // adiciona id para buscas.
        array_unshift($searchColumns, 'id');

        // preparar valor de entrada.
        $values = is_array($value) ? $value : ['all' => $value];

        $queryOptions = [];
        $customOptions = [];
        $inputText = '';

        $mapQueryOption = function ($column, $value) {
            return match ($column) {
                'id' => [
                    'id',
                    '=',
                    $value,
                    'or',
                ],

                default => [
                    $column,
                    'like',
                    "%$value%",
                    'or',
                ]
            };
        };

        foreach ($values as $column => $text) {

            if (empty($text)) {
                continue;
            }

            if ($column !== 'all') {
                $customOptions[$column] = $text;
                continue;
            }

            preg_match("#(\w+)\=(.+)#", $text, $filtered);

            if (! empty($filtered)) {
                //* condição de pesquisa em coluna especifica.
                [, $column, $value] = $filtered;

                $queryOptions = [
                    [$column, '=', $value],
                ];

                break;
            }

            $queryOptions = Arr::map($searchColumns, fn ($column) => $mapQueryOption($column, $text));

            //* texto a ser passando em instruções futuras.
            $inputText = trim($text);
        }

        $i = 0;

        $mapped = fn ($query) => Arr::map(
            $customOptions,
            function ($value, $column) use (&$i, $query, $inputText) {
                $i++;
                $this->applyFilters($query, $value, $column, $inputText, $i > 1);
            }
        );

        $query
            ->when(! empty($queryOptions), fn ($query) => $query->where($queryOptions))
            ->when(
                method_exists($this, 'applyFilters'),
                fn ($query) => $mapped($query)
            );
    }

    /**
     * Formata entrada para busca em lista.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected static function prepareFilterValue($value)
    {
        $newValue = match (gettype($value)) {
            'string' => array_map(fn ($h) => intval($h), array_filter(explode(',', $value))),
            'array' => array_map(fn ($h) => intval($h), $value),
            'int' => [$value],
        };

        return $newValue;
    }

    /**
     * Prepara filtro para campo data.
     *
     * @param  mixed  $value
     * @param  mixed  $date_column
     * @return array
     */
    protected static function prepareFilterDate($value, $date_column = 'date')
    {
        $format = match ($date_column) {
            default => [
                '#\d{4}-\d{2}-\d{2}#',
                '%Y-%m-%d',
            ],
        };

        preg_match($format[0], $value, $matches);

        abort_if(empty($matches), 412, sprintf('o formato da data deve ser (%s)', $format[1]));

        return [
            sprintf("DATE_FORMAT($date_column, '%s') = ?", $format[1]),
            $matches[0],
        ];
    }
}
