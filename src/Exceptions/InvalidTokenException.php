<?php

namespace Tumainimosha\TokenHelper\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    protected $message = 'Invalid Token Supplied';
}
