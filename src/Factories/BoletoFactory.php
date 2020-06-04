<?php

namespace Claudsonm\BoletoWinner\Factories;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Boleto;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;

class BoletoFactory extends BillFactory
{
    /**
     * @throws BoletoWinnerException
     */
    public function make(string $barcodeOrWritableLine): Bill
    {
        $input = preg_replace('/[^0-9]/', '', $barcodeOrWritableLine);
        if (empty($input)) {
            throw BoletoWinnerException::inputRequired();
        }

        $bill = $this->createBill();
        $bill->setBarcode($input);
        if ($bill->isBarcodeValid()) {
            return $bill;
        }

        $bill = $this->createBill();
        $bill->setWritableLine($input);
        if ($bill->isWritableLineValid()) {
            return $bill;
        }

        throw BoletoWinnerException::invalidInput($barcodeOrWritableLine);
    }

    protected function createBill(): Bill
    {
        return new Boleto();
    }

    public function getMyClasses()
    {
        $children = glob(__DIR__.'/../*.php');

        return $children;
    }
}
