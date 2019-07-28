# laravel-log-requests

A quick and easy drop-in Laravel package to add simple logging of http
requests and responses. No configuration required beyond associating the
provided middleware with the routes you want to include.

I created this package with the following in mind:
* capture the request & response of API calls to the app
* leverage latest Laravel features allowing effortless package injection
* easily re-usable across apps with a sensible zero-config default
* allow easily overriding the handler for app or env customizations

## install

```sh
composer require jrebs/laravel-log-requests
```

To use defaults, publish the migration to your application and migrate:

```sh
php artisan vendor:publish --provider=Jrebs\\LogRequests\\Providers\\LogRequestsServiceProvider

php artisan migrate
```

Now you just need to declare routes to be logged. Your `App\Http\Kernel` may
then contain something like this:

```php
protected $middlewareGroups = [
    // ...
    'api' => [
        'throttle:60,1',
        'bindings',
        'log-requests', // Record all API requests to api_requests table
    ],
];
```

Now when you hit routes covered by `api`, you should find them being
stored and can be accessed with the `Jrebs\LogRequests\LoggedRequest` model.

Alternately, you can apply the middleware in a routes file:
```php
// routes/web.php
Route::middleware('log-requests')->group(function () {
    // routes defined in this group closure will all be passed to the handler
});
```

## Accessing Logs

The default log handler uses the provided `Jrebs\LogRequests\LoggedRequest`.
I recommend that if you're going to write any code to access the provided
log models, you should extend the `LoggedRequest` class to something in your
`App` namespace and then you can set up relationships on the model, such as
joining the `user_id` property against your `App\User` model or similar.

## Example Custom Handler

To use your own storage method, skip publishing migrations and
instead only publish `config` so you can provide your own handler:

```sh
php artisan vendor:publish --provider=Jrebs\\LogRequests\\Providers\\LogRequestsServiceProvider --tag=config
```

Now you need to declare your custom handler in `config/log-requests.php`:

```php
return [
    // Set an app-provided Handler to override the package handler
    'handler' => env('LOG_REQUESTS_HANDLER', App\CustomHandler::class),
];
```

As you can see, it is also possible to override this setting at runtime by
editing your `.env` file with similar:

```sh
# Override both the package and the app-provided handler
LOG_REQUESTS_HANDLER=App\CustomHandler
```

A custom handler needs to implement the `LogRequestsHandler` interface,
providing a static `log()` method accepting request, response and duration.
This is a simplistic example of how you might provide your own custom handler
in `app/CustomHandler.php`

```php
/**
 * Example handler - app/CustomHandler.php
 */
namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jrebs\LogRequests\LogRequestsHandler;

class CustomHandler implements LogRequestHandler
{
    public static function log($request, $response, $duration): void
    {
        Log::debug(sprintf(
            "Request: %s\nResponse: %s\nDuration: %s",
            $request,
            $response,
            $duration
        ));
    }
}
```

## todo
* write a todo list
* figure out if there's some sensible way to test the package standalone
