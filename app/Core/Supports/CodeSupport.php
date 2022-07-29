<?php

namespace App\Core\Supports;

use Illuminate\Support\Facades\DB;

final class CodeSupport
{
    /**
     * Valor fixado.
     *
     * @var int
     */
    private int $prefix = 27;

    /**
     * @param string $table
     * @param int $size
     * @return void
     */
    public function __construct(
        private string $table,
        private string $column = 'code',
        private int $size = 8
    ) {
    }

    /**
     * @param string $table
     * @param string $column
     * @param int $size
     * @return static
     */
    public static function factory(string $table, string $column = 'code', int $size = 8): static
    {
        return new self($table, $column, $size);
    }

    /**
     * Gerar codigo unico.
     *
     * @return string
     */
    public function random(): string
    {
        $start = intval(str_pad($this->prefix, $this->size, 0));
        $end = intval(str_pad($this->prefix, $this->size, 9));
        $code = null;

        do {
            $code = random_int($start, $end);
        } while (DB::table($this->table)->where($this->column, $code)->exists());

        return $code;
    }
}
