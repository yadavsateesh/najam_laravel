<?php

namespace App\Http\Middleware;

use App\Models\Api_audit;
use Closure;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class SaveAudit
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
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        Api_audit::create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'api_name' => $request->path(),
            'request_json' => json_encode($request->all(), true),
            'response_json' => $response->getContent(),
            'time_taken' => round(microtime(true) * 1000),
        ]);
    }
}
