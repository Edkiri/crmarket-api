<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            return response()->json([
                'error' => "Validation error",
                'errors'  => $e->errors(),
            ], 422);
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            Log::error('Internal Server Error', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            if (env('APP_ENV') === 'development') {
                return response()->json([
                    'message' => 'Internal Server Error',
                    'error'   => $e->getMessage(),
                    'stack'   => $e->getTraceAsString(),
                ], 500);
            }

            return response()->json([
                'message' => 'Internal Server Error',
            ], 500);
        });
    }
}
