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
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin.guest' => \App\Http\Middleware\Adminredirect::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
            'emp.guest' => \App\Http\Middleware\Employeeredirect::class,
            'emp.auth' => \App\Http\Middleware\EmployeeAuthenticate::class,
            'employee.hr' => \App\Http\Middleware\CheckEmployeeOrHr::class
        ]);

     
        $middleware->redirectTo(
            guests: '/emp/login',
            users: '/emp/dashboard',
        );
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
