<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    const ROLE_SUPER_ADMIN= 21;

    public function handle(Request $request, Closure $next)
    {
        Log::info('Entered isSuperAdmin middleware...');

        $userId = auth()->user()->id;

        $user = User::find($userId);

        $isSuperAdmin = $user->roles->contains(self::ROLE_SUPER_ADMIN);

        if(!$isSuperAdmin){

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Route does not exist.'
                ],
                404
            );
        }
        Log::info('User id '.$userId.' is super admin -> exited middleware correcly.');

        return $next($request);
    }
}
