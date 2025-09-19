<?php

namespace App\Http\Middleware\Setting;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('locale.supported', ['en', 'fr']);
        
        $seg = $request->segment(1);
        $locale = in_array($seg, $supported) ? $seg : config('app.locale');

        app()->setLocale($locale);

        return $next($request);
    }
}
