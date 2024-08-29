<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Responser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role_1, string $role_2 = null): Response
    {
        $roles = [$role_1,$role_2];
        $has_role = false;

        foreach($roles as $role){
            if ($request->user()->hasRole($role)) {
                $has_role = true;
            }
        }

        if(! $has_role){
            abort(403, "Forbidden; You don't have permission to access this resource");
        }

        return $next($request);
    }
}
