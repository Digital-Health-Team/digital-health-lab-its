<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetTimezone;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Number;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);
        $middleware->web(append: [
            SetLocale::class,
            SetTimezone::class,
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /*
         * PHP rejects an oversized POST in the SAPI, before session or CSRF middleware
         * run, so Laravel's validator never sees it and the user gets a raw 413 trace.
         * Turn it into the same message a `max:` rule would have produced.
         */
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $limit = Number::fileSize(UploadedFile::getMaxFilesize());
            $message = __('That upload is too large. This server accepts files up to :limit each.', ['limit' => $limit]);

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            // No session at this point in the stack, so the message rides on the query
            // string; the form page reads ?uploadError= and renders it inline.
            $back = $request->headers->get('referer') ?: route('publish.index');

            return redirect()->to($back.(str_contains($back, '?') ? '&' : '?').'uploadError='.urlencode($message));
        });
    })->create();
