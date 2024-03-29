<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Install;
class SWInstall
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
        try {

            $install = Install::findOrFail(1);

            if ($install->database && $install->account && $install->import && $install->finished ) return redirect()->route('home');

        } catch (\Exception $e) {}

        return $next($request);
    }
}
