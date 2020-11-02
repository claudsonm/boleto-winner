<?php

namespace Claudsonm\BoletoWinner;

use BadMethodCallException;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;
use Claudsonm\BoletoWinner\Factories\BillFactory;

/**
 * @method static bool isValidBoleto(string $barcodeOrWritableLine)
 * @method static bool isValidConvenio(string $barcodeOrWritableLine)
 * @method static bool isValidBarcode(string $barcode)
 * @method static bool isValidWritableLine(string $barcode)
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
     * @throws BoletoWinnerException
     *
     * @return bool
     */
    public static function __callStatic($method, $arguments)
    {
        if (starts_with('isValid', $method)) {
            return self::handleIsValidTypeCall($method, $arguments);
        }

        if (! method_exists(BillFactory::class, $method)) {
            throw new BadMethodCallException("Method `{$method}` does not exist.");
        }

        return BillFactory::getInstance()->{$method}(...$arguments);
    }

    public static function isValid(string $barcodeOrWritableLine): bool
    {
        try {
            BillFactory::getInstance()->createBillInstanceFromString($barcodeOrWritableLine);

            return true;
        } catch (BoletoWinnerException $exception) {
            return false;
        }
    }

    /**
     * @throws BoletoWinnerException
     */
    private static function handleIsValidTypeCall(string $method, array $arguments): bool
    {
        $type = strtolower(substr($method, 7));
        $input = preg_replace('/[^0-9]/', '', ...$arguments);
        $billClass = BillFactory::getInstance()->createBillInstance($type);
        $billClass->setBarcode($input)->setWritableLine($input);

        return $billClass->isBarcodeValid() || $billClass->isWritableLineValid();
    }
}
