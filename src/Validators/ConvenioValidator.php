<?php

namespace Claudsonm\BoletoWinner\Validators;

use Claudsonm\BoletoWinner\Convenio;

class ConvenioValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function verifyWritableLine(string $writableLine): bool
    {
        $writableLine = preg_replace('/[^0-9]/', '', $writableLine);

        if (strlen($writableLine) != 48) {
            return false;
        }

        if ($writableLine[0] != '8') {
            return false;
        }

        if (! in_array($writableLine[1], Convenio::SEGMENT_IDENTIFICATION)) {
            return false;
        }

        $blocks = str_split($writableLine, 12);

        $isModule10 = in_array($writableLine[2], Convenio::USES_MODULE_10);

        $blocksValidated = 0;
        foreach ($blocks as $block) {

            $blockLength = strlen($block) - 1;

            $blockInputNumbers = substr($block, 0, $blockLength);

            $blockVerificationDigit = $this->getLastCharacter($block);

            $digitCalculated = $isModule10 ? module10($blockInputNumbers) : module11($blockInputNumbers);

            if ($digitCalculated === $blockVerificationDigit) {
                $blocksValidated++;
            }
        }

        return 4 === $blocksValidated;
    }

    /**
     * {@inheritdoc}
     */
    public function verifyBarcode(string $barcode): bool
    {
        $barcode = preg_replace('/[^0-9]/', '', $barcode);
        
        if (strlen($barcode) != 44) {
            return false;
        }

        if ($barcode[0] != '8') {
            return false;
        }

        if (! in_array($barcode[1], Convenio::SEGMENT_IDENTIFICATION)) {
            return false;
        }

        $effectiveValueOrReferenceIdentifier = $barcode[2];

        if (! in_array($effectiveValueOrReferenceIdentifier, Convenio::AVAILABLE_CODES)) {
            return false;
        }

        $barcodeVerificationDigit = $barcode[3];

        $blockInputNumbers = substr($barcode, 0, 3);

        $blockInputNumbers .= substr($barcode, 4);

        $digitCalculated = in_array($effectiveValueOrReferenceIdentifier, Convenio::USES_MODULE_10)
            ? module10($blockInputNumbers)
            : module11($blockInputNumbers);

        return $digitCalculated === $barcodeVerificationDigit;
    }

    /**
     * Returns the last character of the input string.
     */
    private function getLastCharacter(string $input): string
    {
        return substr($input, -1);
    }
}
