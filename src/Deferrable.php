<?php

namespace Raicem\Defer;

trait Deferrable
{
    public static function defer()
    {
        $deferrer = app()->make(Deferrer::class);

        $job = new static(...func_get_args());

        $deferrer->push($job);
    }
}
