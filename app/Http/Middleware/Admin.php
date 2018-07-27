<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
class Admin
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
        // if(! auth()->user()->admin){ //tras crear el método isAdmin en el modelo User puedo hacer
        if(! auth()->user()->isAdmin()){
            //return response()->view('forbidden',[],403); // con una vista
            throw new AuthorizationException; // hago use de Illuminate\Auth\Access\AuthorizationException
        }

        return $next($request);
    }
}
