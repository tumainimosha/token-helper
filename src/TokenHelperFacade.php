<?php

namespace Tumainimosha\TokenHelper;

use Illuminate\Support\Facades\Facade;

class TokenHelperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'token-helper';
    }
}
