<?php

namespace App\Exceptions;

use App\Core\Api\Exception as ApiException;
use App\Core\Api\HttpStatusCode;
use App\Core\Api\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Obtem numero do status.
     *
     * @return int
     */
    private function extractStatusCode(Throwable $e)
    {
        $errors = array_values(
            array_filter(
                [
                    $e instanceof HttpException ? $e->getStatusCode() : HttpStatusCode::BAD_REQUEST,
                    $e instanceof ApiException ? $e->getStatusCode() : null,
                    $e instanceof AuthenticationException ? HttpStatusCode::UNAUTHORIZED : null,
                    $e instanceof AuthorizationException ? HttpStatusCode::FORBIDDEN : null,
                    $e instanceof ValidationException ? HttpStatusCode::UNPROCESSABLE_ENTITY : null,
                    $e instanceof NotFoundHttpException ? HttpStatusCode::NOT_FOUND : null,
                    $e instanceof NotFoundResourceException ? HttpStatusCode::NOT_FOUND : null,
                    $e instanceof ModelNotFoundException ? HttpStatusCode::NOT_FOUND : null,
                ]
            )
        );

        return empty($errors) ? HttpStatusCode::INTERNAL_SERVER_ERROR : end($errors);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     */
    public function render($request, Throwable $e)
    {
        return Response::send($e, $this->extractStatusCode($e));
    }
}
