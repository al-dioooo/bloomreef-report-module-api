<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAPIs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->getMethod() !== "GET" && $response->status() === 200) {
            $log = [
                'URI' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                'RESPONSE' => json_decode($response->getContent())
            ];

            Log::channel('api')->info('An API Called', $log);
            Log::channel('slack_notification')->info('An API Called', $log);
        }

        if ($response->status() !== 200) {
            $log = [
                'URI' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                'RESPONSE' => json_decode($response->getContent()),
                'STATUS' => $response->status()
            ];

            Log::channel('api')->error('An error occured', $log);
            Log::channel('slack_notification')->error('An error occured', $log);
        }

        return $response;
    }
}
