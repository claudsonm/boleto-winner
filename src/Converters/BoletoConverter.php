<?php

namespace Claudsonm\BoletoWinner\Converters;

use Claudsonm\BoletoWinner\Bill;

class BoletoConverter implements Converter
{
    /**
     * {@inheritdoc}
     */
    public function toBarcode(Bill $bill): string
    {
        $writableLine = $bill->getWritableLine();

        $barcodeParts = [
            'bankCode' => substr($writableLine, 0, 3),
            'currency' => substr($writableLine, 3, 1),
            'financialInfo' => substr($writableLine, 32),
            'firstFreeFields' => substr($writableLine, 4, 5),
            'secondFreeFields' => substr($writableLine, 10, 10),
            'thirdFreeFields' => substr($writableLine, 21, 10),
        ];

        return implode('', $barcodeParts);
    }

    /**
     * {@inheritdoc}
     */
    public function toWritableLine(Bill $bill): string
    {
        $barcode = $bill->getBarcode();
        
        $blocks = [
            substr($barcode, 0, 4).substr($barcode, 19, 5),
            substr($barcode, 24, 10),
            substr($barcode, 34, 10),
        ];

        foreach ($blocks as &$block) {
            $block .= module10($block);
        }
        $blocks[] = substr($barcode, 4, 1);
        $blocks[] = substr($barcode, 5, 14);

        return implode('', $blocks);
    }
}
