<?php

namespace Claudsonm\BoletoWinner\Validators;

use Claudsonm\BoletoWinner\Bill;

interface Validator
{
    /**
     * Checks if the given bill has a valid writable line.
     */
    public function verifyWritableLine(Bill $bill): bool;

    /**
     * Checks if the given bill has a valid barcode.
     */
    public function verifyBarcode(Bill $bill): bool;
}
