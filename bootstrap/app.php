<?php

use App\Utils\JsonResponseAPI;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (RouteNotFoundException $exceptions) {
            return JsonResponseAPI::errorResponse('Not Found', 404);
        });
        $exceptions->render(function (NotFoundHttpException $exceptions) {
            return JsonResponseAPI::errorResponse($exceptions->getMessage().' Not Found', 404);
        });
        $exceptions->render(function (ModelNotFoundException $exceptions) {
            return JsonResponseAPI::errorResponse(Str::afterLast($exceptions->getMessage(), '\\').' Not- Found', 404);
        });
    })->create();
