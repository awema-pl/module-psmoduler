<?php

namespace AwemaPL\Psmoduler\Facades;

use AwemaPL\Psmoduler\Contracts\Psmoduler as PsmodulerContract;
use Illuminate\Support\Facades\Facade;

class Psmoduler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PsmodulerContract::class;
    }
}
