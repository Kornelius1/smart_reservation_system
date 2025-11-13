<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',    
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
       $middleware->validateCsrfTokens(except: [
         'api/doku/notification'
        ]);
        $middleware->trustProxies(at: '*');
    })


    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('reservations:update-status')->everyMinute();
    })


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
