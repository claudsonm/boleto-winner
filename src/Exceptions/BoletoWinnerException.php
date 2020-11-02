<?php

namespace Claudsonm\BoletoWinner\Exceptions;

class BoletoWinnerException extends \Exception
{
    public static function invalidInput(string $input): self
    {
        return new static("The value `{$input}` given is not a valid barcode or writable line.");
    }

    public static function inputRequired(): self
    {
        return new static("You must provide a numeric string.");
    }

    public static function invalidBillClass(string $class): self
    {
        $superclass = \Claudsonm\BoletoWinner\Bill::class;

        return new static("The class `{$class}` must be a subclass of `{$superclass}`.");
    }

    public static function unsupportedType(string $type): self
    {
        return new static("The type `{$type}` is not supported, no class is associated with it.");
    }
}
