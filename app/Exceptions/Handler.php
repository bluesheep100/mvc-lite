<?php

namespace App\Exceptions;

use Exception;

class Handler
{
    public static function handle(Exception $exception)
    {
        die(
            response($exception->getCode())
                ->withError($exception->getMessage())
        );
    }
}