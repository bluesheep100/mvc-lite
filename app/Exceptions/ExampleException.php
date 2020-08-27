<?php

namespace App\Exceptions;

use Exception;

class ExampleException extends Exception
{
    protected $code = 500;
    protected $message = 'Oh no! Something went wrong.. or maybe this is just an example?';
}