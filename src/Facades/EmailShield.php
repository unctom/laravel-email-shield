<?php

namespace Unctom\EmailShield\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Unctom\EmailShield\EmailShield
 */
class EmailShield extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Unctom\EmailShield\EmailShield::class;
    }
}
