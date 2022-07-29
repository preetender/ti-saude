<?php

namespace App\Core\Api;

use Exception as BaseException;
use Illuminate\Support\Str;

class Exception extends BaseException
{
    public int $status_code = 400;

    /**
     * @param int $code
     * @return static
     */
    public function setStatusCode(int $code)
    {
        $this->status_code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    /**
     * @param mixed $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = Str::of(sprintf("%s-%s", env('APP_NAME'), $code))
            ->trim('-')
            ->upper()
            ->__toString();

        return $this;
    }
}
