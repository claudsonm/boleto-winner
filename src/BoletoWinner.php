<?php

namespace Claudsonm\BoletoWinner;

use BadMethodCallException;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;
use Claudsonm\BoletoWinner\Factories\BillFactory;
use Claudsonm\BoletoWinner\Validators\BoletoValidator;
use Claudsonm\BoletoWinner\Validators\ConvenioValidator;
use Throwable;

/**
 * @method static bool isValidBoleto(string $barcodeOrWritableLine)
 * @method static bool isValidConvenio(string $barcodeOrWritableLine)
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

        throw new BadMethodCallException("Method `{$method}` does not exist.");
    }

    /**
     * @throws BoletoWinnerException
     */
    public static function makeBill(string $barcodeOrWritableLine): Bill
    {
        return BillFactory::getInstance()
            ->createFromBarcodeOrWritableLine($barcodeOrWritableLine);
    }

    /**
     * @throws BoletoWinnerException
     */
    public static function toWritableLine(string $barcode): string
    {
        return BillFactory::getInstance()
            ->createFromBarcode($barcode)
            ->getWritableLine();
    }

    /**
     * @throws BoletoWinnerException
     */
    public static function toBarcode(string $writableLine): string
    {
        return BillFactory::getInstance()
            ->createFromWritableLine($writableLine)
            ->getBarcode();
    }

    public static function isValid(string $barcodeOrWritableLine): bool
    {
        try {
            BillFactory::getInstance()->createFromBarcodeOrWritableLine($barcodeOrWritableLine);

            return true;
        } catch (BoletoWinnerException $exception) {
            return false;
        }
    }

    public static function isValidWritableLine(string $writableLine): bool
    {
        try {
            BillFactory::getInstance()->createFromWritableLine($writableLine);

            return true;
        } catch (BoletoWinnerException $exception) {
            return false;
        }
    }

    public static function isValidBarcode(string $barcode): bool
    {
        try {
            BillFactory::getInstance()->createFromBarcode($barcode);

            return true;
        } catch (BoletoWinnerException $exception) {
            return false;
        }
    }

    public static function isBoletoByBarcode(string $barcode): bool
    {
        try {
            $writableLine = self::toWritableLine($barcode);

            $isBoleto = (new BoletoValidator())->verifyWritableLine($writableLine);

            return $isBoleto;

        } catch (Throwable $e) {
            return false;
        }
    }

    public static function isBoletoByWritableLine(string $writableLine): bool
    {
        try {
            $writableLineClean = preg_replace('/[^0-9]/', '', $writableLine);

            if (empty($writableLineClean)) {
                throw BoletoWinnerException::inputRequired();
            }

            $isBoleto = (new BoletoValidator())->verifyWritableLine($writableLineClean);

            return $isBoleto;

        } catch (Throwable $e) {
            return false;
        }
    }

    public static function isConvenioByBarcode(string $barcode): bool
    {
        try {
            $writableLine = self::toWritableLine($barcode);

            $isConvenio = (new ConvenioValidator())->verifyWritableLine($writableLine);

            return $isConvenio;

        } catch (Throwable $e) {
            return false;
        }
    }

    public static function isConvenioByWritableLine(string $writableLine): bool
    {
        try {
            $writableLineClean = preg_replace('/[^0-9]/', '', $writableLine);

            if (empty($writableLineClean)) {
                throw BoletoWinnerException::inputRequired();
            }

            $isConvenio = (new ConvenioValidator())->verifyWritableLine($writableLineClean);

            return $isConvenio;

        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * @throws BoletoWinnerException
     */
    private static function handleIsValidTypeCall(string $method, array $arguments): bool
    {
        $type = strtolower(substr($method, 7));
        $input = self::sanitizeInput(array_shift($arguments));
        $billClass = BillFactory::getInstance()->createBillInstance($type);
        $billClass->setBarcode($input)->setWritableLine($input);

        return $billClass->isBarcodeValid() || $billClass->isWritableLineValid();
    }

    private static function sanitizeInput(string $input): string
    {
        return preg_replace('/[^0-9]/', '', $input);
    }
}
