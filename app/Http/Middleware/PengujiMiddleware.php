<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengujiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('role') !== 'penguji') {
            return redirect()->route('login.penguji')
                ->withErrors(['email' => 'Silakan login sebagai penguji.']);
        }

        return $next($request);
    }
}