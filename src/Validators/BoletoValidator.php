<?php

namespace Claudsonm\BoletoWinner\Validators;

use Claudsonm\BoletoWinner\Bill;

class BoletoValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function verifyWritableLine(Bill $bill): bool
    {
        $writableLine = $bill->getWritableLine();
        $blocks[] = substr($writableLine, 0, 10);
        $blocks[] = substr($writableLine, 10, 11);
        $blocks[] = substr($writableLine, 21, 11);

        $blocksValidated = 0;
        foreach ($blocks as $block) {
            $blockLength = strlen($block) - 1;
            $blockInputNumbers = substr($block, 0, $blockLength);
            $blockVerificationDigit = $this->getLastCharacter($block);
            if (module10($blockInputNumbers) === $blockVerificationDigit) {
                $blocksValidated++;
            }
        }

        return 3 === $blocksValidated;
    }

    /**
     * {@inheritdoc}
     */
    public function verifyBarcode(Bill $bill): bool
    {
        $barcode = $bill->getBarcode();
        $barcodeVerificationDigit = substr($barcode, 4, 1);
        $blockInputNumbers = substr($barcode, 0, 4);
        $blockInputNumbers .= substr($barcode, 5);

        return $this->calculateBarcodeDigit($blockInputNumbers) === $barcodeVerificationDigit;
    }

    /**
     * Returns the last character of the input string.
     */
    private function getLastCharacter(string $input): string
    {
        return substr($input, -1);
    }

    /**
     * Computes the barcode verification digit.
     */
    private function calculateBarcodeDigit(string $inputNumbers): string
    {
        $digit = module11($inputNumbers);
        if ('0' === $digit) {
            return '1';
        }

        return $digit;
    }
}
