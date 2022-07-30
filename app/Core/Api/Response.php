<?php

namespace App\Core\Api;

use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Throwable;

class Response implements HttpStatusCode
{
    /**
     * @param  int  $code
     * @return mixed
     */
    public static function statusText(int $code = null)
    {
        $messages = __('common.status');

        return $code ? $messages[$code] : $messages;
    }

    /**
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function send($data = null, $status = 200, $headers = [])
    {
        $content = $data;

        if ($status >= 400 && $data instanceof Throwable) {
            $errors = [
                $data->getMessage(),
            ];

            if ($status === 422 && $data instanceof ValidationException) {
                $errors = Arr::collapse(array_values($data->errors()));
            }

            $normalize = [
                QueryException::class => fn () => __('common.status.400'),
                ValidationException::class => fn () => __('common.status.422'),
            ];

            $message = in_array(get_class($data), array_keys($normalize))
                ? $normalize[get_class($data)]()
                : __("common.status.$status");

            $content = array_filter(
                [
                    'message' => $message,
                    'code' => $data->getCode(),
                    'line' => !env('APP_DEBUG') ?: $data->getLine(),
                    'file' => !env('APP_DEBUG') ?: $data->getFile(),
                    'exception' => !env('APP_DEBUG') ?: get_class($data),
                    'trace' => !env('APP_DEBUG') ?: collect($data->getTrace())->map(function ($trace) {
                        return Arr::except($trace, ['args']);
                    })->take(5)->all(),
                    'errors' => $errors,
                ]
            );
        }

        if (is_string($data)) {
            $content = ['message' => $data];
        }

        $headers['Content-type'] = 'application/json';
        $headers['Accept'] = 'application/json';

        return response($content, $status, $headers);
    }

    /**
     * Status 200.
     *
     * @param $data
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondOk($data, $headers = [])
    {
        return static::send($data, self::OK, $headers);
    }

    /**
     * Status 201.
     *
     * @param $data
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondCreated($data, $headers = [])
    {
        return static::send($data, self::CREATED, $headers);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public static function respondNoContent()
    {
        return static::send(status: self::NO_CONTENT);
    }

    /**
     * Status 400.
     *
     * @param $error
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondBadRequest($error, $headers = [])
    {
        return static::send($error, self::BAD_REQUEST, $headers);
    }

    /**
     * Status 404.
     *
     * @param $error
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondNotFound($error, $headers = [])
    {
        return static::send($error, self::NOT_FOUND, $headers);
    }

    /**
     * Status 500.
     *
     * @param $error
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondInternal($error, $headers = [])
    {
        return static::send($error, self::INTERNAL_SERVER_ERROR, $headers);
    }

    /**
     * Status 502.
     *
     * @param $error
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondBadGateway($error, $headers = [])
    {
        return static::send($error, self::BAD_GATEWAY, $headers);
    }

    /**
     * Status 503.
     *
     * @param $error
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public static function respondServerUnavailable($error, $headers = [])
    {
        return static::send($error, self::SERVICE_UNAVAILABLE, $headers);
    }

    /**
     * @param  bool  $condition
     * @param  string  $message
     * @param  mixed  $code
     * @return void
     */
    public static function sendIf(bool $condition, string $message = null, int $status = 400, mixed $code = null)
    {
        $exception = new Exception($message);
        $exception->setStatusCode($status);
        $exception->setCode($code);

        throw_if($condition, $exception);
    }
}
