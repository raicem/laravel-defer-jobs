<?php

namespace Raicem\Defer\Middleware;

use Closure;
use Raicem\Defer\Deferrer;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Str;

class ExecuteDeferredJobs
{
    private $deferrer;
    private $dispatcher;

    public function __construct(Deferrer $deferrer, Dispatcher $dispatcher)
    {
        $this->deferrer = $deferrer;
        $this->dispatcher = $dispatcher;
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate()
    {
        $jobs = $this->deferrer->getJobs();

        foreach ($jobs as $job) {
            $this->dispatcher->dispatchNow($job);
        }
    }
}
