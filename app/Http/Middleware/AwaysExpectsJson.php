<?php

namespace App\Http\Middleware;

use Closure;

class AwaysExpectsJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!config('app.debug'))
            $request->headers->add(['Accept' => 'application/json']);

        return $next($request);
    }
}
