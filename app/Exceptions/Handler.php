<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Loyalty\Exceptions\LoyaltyApiError;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        BusinessException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->reportable(static function (Throwable $e): void {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (($e instanceof ValidationException || $e instanceof LoyaltyApiError) && Request::expectsJson()) {
            return new JsonResponse([
                'error' => [
                    'message' => $e->getMessage(),
                ]
            ], 400);
        }

        if ($e instanceof BusinessException) {
            if ($request->ajax()) {
                $json = [
                    'success' => false,
                    'error' => $e->getUserMessage(),
                ];

                return response()->json($json, 400);
            }

            /** @noinspection MissedFieldInspection */
            $redirect = redirect($e->getRedirectTo() ?? url()->previous())
                ->withInput();

            match (true) {
                $e instanceof ToastException => $redirect->with('toast', [
                    'type' => 'error',
                    'message' => $e->getUserMessage(),
                ]),
                default => $redirect->withErrors([
                    'business_error' => trans($e->getUserMessage()),
                ]),
            };

            return $redirect;
        }

        return parent::render($request, $e);
    }

    public function report(Throwable $e): void
    {
        if (app()->bound('sentry') && app()->environment('production') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }
}
