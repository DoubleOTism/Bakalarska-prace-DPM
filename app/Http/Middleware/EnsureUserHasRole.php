<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    public function handle($request, Closure $next, ...$roles)
    {
	$user = Auth::user();

	if (!$user || !$user->roles()->whereIn('name', $roles)->exists() || $user->status !== 'activated') {
            return redirect('/')->with('error', 'Nemáte požadovaná práva pro přístup k této části aplikace, nebo váš účet není aktivován.');
        }
        return $next($request);
    }
}
