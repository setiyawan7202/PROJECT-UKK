<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockWeekend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (now()->isWeekend()) {
            return redirect()->back()->with('error', 'Pelayanan tutup pada hari Sabtu dan Minggu.');
        }

        return $next($request);
    }
}
