<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    //middleware JWT
    public function render($request, Throwable $exception)
    {
        if ($exception instanceOf TokenInvalidException) {
            return response()->json([
                'error' => 'Token is Invalid'
            ],400);
        }
        elseif ($exception instanceOf TokenExpiredException) {
            return response()->json([
                'error' => 'Token is Expired'
            ],400);
        }
        elseif ($exception instanceOf JWTException) {
            return response()->json([
                'error' => 'There is problem with your token'
            ],400);
        } elseif ($exception instanceOf ValidationException) {
            return response()->json([
                'error' => $exception->errors()
            ],400);
        }

        return parent::render($request, $exception);
    }
}
