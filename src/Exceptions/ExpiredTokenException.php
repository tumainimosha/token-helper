<?php

namespace Tumainimosha\TokenHelper\Exceptions;

use Exception;

class ExpiredTokenException extends Exception
{
    protected $message = 'Token Expired!';
}
