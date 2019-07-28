<?php

namespace Jrebs\LogRequests;

interface LogRequestsHandler
{
    /**
     * Handle logging a request/response pair.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response|\Illuminate\Http\JsonResponse $response
     * @param float $duration
     *
     * @return void
     */
    public static function log($request, $response, $duration): void;
}
