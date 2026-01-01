<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(api: __DIR__.'/../routes/api.php')
    ->withMiddleware(function (Middleware $middleware) { $middleware->alias(['role'=>\Spatie\Permission\Middleware\RoleMiddleware::class]); $middleware->throttleApi('60,1'); })
    ->withExceptions(function () {})->create();
