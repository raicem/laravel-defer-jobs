## Laravel Defer Jobs

Defer your jobs to be executed after the response is sent\*. Without a queue system.

\*Only if you are using `php-fpm`. See requirements section for more info.

### Why

Sometimes you don't want to setup a queue system just to do some light work outside of the request and response cycle. This package is created to show it is possible to extract some work out of the
request and response cycle without using a queue system. Please note, this is more of a proof of concept and I believe there can be more robust implementations.

### How

`php-fpm` exposes a function called [fastcgi_finish_request](https://www.php.net/manual/en/function.fastcgi-finish-request.php) which is used by the Laravel to send the request to the webserver. Laravel also exposes a function in the middlewares that you can do some work after this `fastcgi_finish_request` function is called. This package is basically just a service that collects jobs to be deferrered and runs them in the `terminate` function in the [middleware](https://laravel.com/docs/5.8/middleware#terminable-middleware).

### Installation

Install package using composer

```bash
composer require raicem/laravel-defer-jobs
```

This package support [auto discovery](https://laravel-news.com/package-auto-discovery). So the service provider should be registered automatically.

You have to register the middleware manually. You can register it in the `app/Http/Kernel.php` file.

```php
// app/Http/Kernel.php
 protected $middleware = [
     //..
    \App\Http\Middleware\TrustProxies::class,
    \Raicem\Defer\Middleware\ExecuteDeferredJobs::class,
];
```

Then you are ready to go.

### Usage

This package leverages Laravel's job implementation. Jobs are objects to be sent to the [queue system](https://laravel.com/docs/5.8/queues). 

First you can [create a job](https://laravel.com/docs/5.8/queues#creating-jobs).

```php
php artisan make:job LogSomething
```

In your job class implement the `Deferrable` trait.

```php
use Raicem\Defer\Deferrable;

class LogStuff implements ShouldQueue
{
    use Deferrable, SerializesModels;

    
    public function __construct(Podcast $podcast)
    {
    }

    public function handle(AudioProcessor $processor)
    {
        // handle job
    }
}
```

Now you can call the job anywhere you want. For example in your controller.


```php
// HomeController.php

use App\Jobs\LogStuff;

class HomeController 
{
    public function action()
    {
        // do stuff
        LogStuff::defer('my log message');

        return view('welcome');
    }
}

```

and your job will be executed after the response is sent.

Please note this package does not really care about your queue system. You can still create a job to be sent to queue, following the standard [Laravel documentation](https://laravel.com/docs/5.8/queues).

By using jobs, later down the line you can actually decide to send them to a proper queue system to be executed by simply changing a line of code. You can simply [dispatch them](https://laravel.com/docs/5.8/queues#dispatching-jobs) instead of deferring them.

### Use cases

- Sending emails
- Logging
- Gathering analytics data from request or response
- Making API calls

Basically, some light work that would slow your response down.

### Requirements

This package will only do its effect if you are using php-fpm to execute your code. If you don't use `php-fpm` and still try to use it, it will have no effect and it will run the jobs before the response is sent.

### Warning

Please note that, if you want to do heavy work using this method, this package is not very suitable for you. `php-fpm` will keep the process open until your script ends. By doing so you may hit the maximum limit of the child processes of `php-fpm`. If you hit that limit, you may not receive any new requests.
