<?php

namespace TestMonitor\CalculatedColumns\Exceptions;

use Exception;

class InvalidCalculatedColumn extends Exception
{
    public static function make($value): static
    {
        return new static("Column value `{$value}` is invalid.");
    }
}
