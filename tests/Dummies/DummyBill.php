<?php

namespace Claudsonm\BoletoWinner\Tests\Dummies;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Converters\Converter;
use Claudsonm\BoletoWinner\Validators\Validator;

class DummyBill extends Bill
{
    protected function useConverter(): Converter
    {
        return new class() implements Converter {
            public function toBarcode(Bill $bill): string
            {
                return 'converted bar code';
            }

            public function toWritableLine(Bill $bill): string
            {
                return 'converted writable line';
            }
        };
    }

    protected function useValidator(): Validator
    {
        return new class() implements Validator {
            public function verifyWritableLine(Bill $bill): bool
            {
                return true;
            }

            public function verifyBarcode(Bill $bill): bool
            {
                return true;
            }
        };
    }
}
