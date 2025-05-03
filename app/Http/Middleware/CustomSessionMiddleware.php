<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CustomSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Determine the intended guard from the route or request
        $guard = $this->determineGuard($request);
        
        if ($guard) {
            // Set the session name before any authentication checks
            $sessionName = config("session.names.{$guard}", config('session.cookie'));
            Config::set('session.cookie', $sessionName);
            
            // Set specific session configuration for this guard if it exists
            if ($guardConfig = config("session.guards.{$guard}")) {
                foreach ($guardConfig as $key => $value) {
                    Config::set("session.{$key}", $value);
                }
            }
        }

        return $next($request);
    }

    private function determineGuard(Request $request)
    {
        // Check URL path to determine guard
        if (str_starts_with($request->path(), 'superadmin')) {
            return 'superadmin';
        }
        if (str_starts_with($request->path(), 'admin')) {
            return 'admin';
        }
        if (str_starts_with($request->path(), 'customer')) {
            return 'customer';
        }

        // Check route middleware for guard
        $route = $request->route();
        if ($route && $route->middleware()) {
            $authMiddleware = collect($route->middleware())
                ->first(function ($middleware) {
                    return str_starts_with($middleware, 'auth:');
                });

            if ($authMiddleware) {
                return str_replace('auth:', '', $authMiddleware);
            }
        }

        return null;
    }
} 