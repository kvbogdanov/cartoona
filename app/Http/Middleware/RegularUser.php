<?php

namespace App\Http\Middleware;

use Closure;


class RegularUser
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

        if ($request->user() && $request->user()->role->name == 'admin')
            return redirect('/admin');

        return $next($request);
    }
}
