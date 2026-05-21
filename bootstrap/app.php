<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // ValidationException → 422
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'code' => 'VALIDATION_FAILED',
                        'message' => 'The given data was invalid.',
                        'status' => 422,
                        'details' => $e->errors(),
                    ]
                ], 422);
            }
        });

        // ModelNotFoundException → 404
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                $model = class_basename($e->getModel());
                return response()->json([
                    'error' => [
                        'code' => 'RESOURCE_NOT_FOUND',
                        'message' => "{$model} not found.",
                        'status' => 404,
                        'details' => [],
                    ]
                ], 404);
            }
        });

        // AuthenticationException → 401
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'code' => 'UNAUTHENTICATED',
                        'message' => 'Authentication required.',
                        'status' => 401,
                        'details' => [],
                    ]
                ], 401);
            }
        });

        // AuthorizationException → 403
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'code' => 'FORBIDDEN',
                        'message' => 'You do not have permission to perform this action.',
                        'status' => 403,
                        'details' => [],
                    ]
                ], 403);
            }
        });

        // QueryException (SQLSTATE 23000 = integrity constraint violation) → 409
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if ($request->expectsJson() && $e->getCode() === '23000') {
                $code = str_contains($e->getMessage(), 'foreign key') ? 'DEPENDENCY_CONFLICT' : 'DUPLICATE_RECORD';
                return response()->json([
                    'error' => [
                        'code' => $code,
                        'message' => $code === 'DEPENDENCY_CONFLICT'
                            ? 'Cannot complete operation due to related records.'
                            : 'A record with this data already exists.',
                        'status' => 409,
                        'details' => [],
                    ]
                ], 409);
            }
        });

        // Catch-all → 500
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                \Illuminate\Support\Facades\Log::error('Unhandled exception', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'error' => [
                        'code' => 'INTERNAL_ERROR',
                        'message' => 'An unexpected error occurred. Please try again later.',
                        'status' => 500,
                        'details' => [],
                    ]
                ], 500);
            }
        });
    })->create();
