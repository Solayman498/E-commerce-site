<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // চেক করছে ইউজার লগইন করা কি না এবং সে অ্যাডমিন কি না
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // অ্যাডমিন না হলে ৪-০-৪ পেজ দেখাবে (আপনার রিকোয়েস্ট অনুযায়ী)
        abort(404);
    }
}