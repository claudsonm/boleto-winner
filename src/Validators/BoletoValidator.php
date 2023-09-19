<?php

namespace Claudsonm\BoletoWinner\Validators;

class BoletoValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function verifyWritableLine(string $writableLine): bool
    {
        if (strlen($writableLine) != 47) {
            return false;
        }

        if ($writableLine[0] == '8') {
            return false;
        }

        $blocks = [
            substr($writableLine, 0, 10),
            substr($writableLine, 10, 11),
            substr($writableLine, 21, 11),
        ];

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
    public function verifyBarcode(string $barcode): bool
    {
        if (strlen($barcode) != 44) {
            return false;
        }

        if ($barcode[0] == '8') {
            return false;
        }

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
