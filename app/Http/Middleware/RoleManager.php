<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $user_id = auth()->id();
        $user = User::findOrFail($user_id); 
        if ($user["role_id"] == 1){
            return $next($request);
        }
        return response()->json([
            'message' => 'Unauthorized, make sure using manager kebun account'
        ], 400);
    }
}
