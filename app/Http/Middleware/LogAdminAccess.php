<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if user is logged in
        if (auth()->check()) {
            // Log only main GET page hits that are not Livewire background requests and not AJAX
            if ($request->isMethod('GET') && ! $request->hasHeader('X-Livewire') && ! $request->ajax()) {
                try {
                    DB::table('admin_access_logs')->insert([
                        'user_id' => auth()->id(),
                        'ip_address' => $request->ip() ?? '127.0.0.1',
                        'user_agent' => $request->userAgent() ?? 'Unknown',
                        'accessed_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    logger()->error('Failed to log admin access: ' . $e->getMessage());
                }
            }
        }

        return $response;
    }
}
