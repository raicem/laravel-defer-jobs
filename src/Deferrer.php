<?php

namespace Raicem\Defer;

use Illuminate\Contracts\Queue\Job;

class Deferrer
{
    private $jobs = [];

    public function push($job)
    {
        $this->jobs[] = $job;
    }

    public function getJobs()
    {
        return $this->jobs;
    }
}
