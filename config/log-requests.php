<?php

return [
    // The default handler is Jrebs\LogRequests\Http\Middleware\LogRequests
    'handler' => env('LOG_REQUESTS_HANDLER', null),
];
