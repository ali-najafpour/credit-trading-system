<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssPrevention
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userInput = $request->all();
        array_walk_recursive($userInput, function(&$userInput){
            if(is_string($userInput)){
                $userInput = strip_tags($userInput);

                $search = ["&lt", "&gt", "etc/", "etc/passwd", "#exec", "&quot", "/bin"];
                $replace = "";
                $subject = $userInput;
                $string = strtolower($userInput);
                foreach($search as $needle){
                    $find = str_contains($string,$needle);
                    if($find){
                        abort(403, 'Access denied ...');
                    }
                }
                $userInput = str_ireplace($search, $replace, $subject);
            }
        });
        $request->merge($userInput);

        return $next($request);
    }
}
