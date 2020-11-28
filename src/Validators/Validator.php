<?php

namespace Claudsonm\BoletoWinner\Validators;

interface Validator
{
    /**
     * Checks if the given bill has a valid writable line.
     */
    public function verifyWritableLine(string $writableLine): bool;

    /**
     * Checks if the given bill has a valid barcode.
     */
    public function verifyBarcode(string $barcode): bool;
}
