<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return mixed|Response
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $language = Str::of($request->header("Accept-Language"))->explode(';')->first();

        if ($language) {
            $language = Str::of($language)->explode(',')->last();
        }

        if ($request->hasHeader("Accept-Language")) {
            App::setLocale($language);
        }

        return $next($request);
    }
}
