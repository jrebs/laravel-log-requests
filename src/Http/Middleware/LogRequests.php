<?php

namespace Jrebs\LogRequests\Http\Middleware;

use Closure;
use Jrebs\LogRequests\LoggedRequest;
use Jrebs\LogRequests\LogRequestsHandler;

class LogRequests implements LogRequestsHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $startTime;
        $handler = config('log-requests.handler') ?? self::class;
        $handler::log($request, $response, $duration);

        return $response;
    }

    /**
     * Record the request and response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\JsonResponse $response
     * @param float $duration
     *
     * @return void
     */
    public static function log($request, $response, $duration): void
    {
        $requestData = [
            'user_id' => auth()->user()->id ?? null,
            'duration' => round($duration, 2),
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'ip' => $request->getClientIp(),
            'request' => serialize($request->input()),
            'status' => $response->status(),
            'response' => serialize($response->getContent()),
        ];
        LoggedRequest::create($requestData);
    }
}
