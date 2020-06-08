<?php

namespace Claudsonm\BoletoWinner;

use BadMethodCallException;
use Claudsonm\BoletoWinner\Factories\BillFactory;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;

/**
 * @method static array|string[] getBills()
 * @method static void load(string $class)
 * @method static Bill make(string $barcodeOrWritableLine)
 *
 * @see BillFactory
 */
class BoletoWinner
{
    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @throws BadMethodCallException
     */
    public static function __callStatic($method, $arguments)
    {
        if (! method_exists(BillFactory::class, $method)) {
            throw new BadMethodCallException("Method `{$method}` does not exist.");
        }

        return (new BillFactory())->{$method}(...$arguments);
    }

    public static function isValid(string $barcode): bool
    {
        try {
            self::make($barcode);

            return true;
        } catch (BoletoWinnerException $exception) {
            return false;
        }
    }
}
