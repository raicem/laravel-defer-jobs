
Laravel Defer
------------------

Defer your jobs to be executed after the response is sent*. Without a queue system.
* Only if you are using php-fpm. See requirements section for more info.

### Why

Sometimes you don't want to setup a queue system just to do some light work outside of the request and response cycle. This package is created to show it is possible to extract some work out of the 
request and response cycle without using a queue system. Please note, this is more of a proof of concept and I believe there can be more robust implementations.

### How

php-fpm exposes a function called `fastcgi_finish_request` which is used by the Laravel to send the request to the webserver. Laravel also exposes a function in the middlewares that you can do some work after this `fastcgi_finish_request` function is called. This package is basically just a service that collects jobs to be deferrered and runs them in the `terminate` function in the middleware.

### Usage

This package leverages Laravel's job implementation. Jobs are Laravel's objects to be sent to the queue system. However after installing the package and including the `Deferrable` trait in your job class, you can simply call:

MyJob::defer('my message')`

and your job will be executed after the response is sent.

Please note this package does not really care about your queue system. You can still create a job to be sent to queue, following the Laravel documentation. 

### Use cases

- Sending emails
- Logging
- Gathering analytics data from request or response
- Making API calls

Basically, some light work that would slow your response down. 

### Requirements
This package will only do its effect if you are using php-fpm to execute your code. If you don't use php-fpm and still try to use it, it will have no effect and it will run the jobs before the response is sent. 

### Warning
Please note that, if you want to do heavy work using this method, this package is not very suitable for you. php-fpm will keep the process open until your script ends. By doing so you may hit the maximum limit of the child processes of php-fpm. If you hit that limit, you may not receive any new requests.
