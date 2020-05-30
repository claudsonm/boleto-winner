<?php

namespace Claudsonm\BoletoWinner\Converters;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Convenio;

class ConvenioConverter implements Converter
{
    /**
     * {@inheritdoc}
     */
    public function toBarcode(Bill $bill): string
    {
        $blocks = str_split($bill->getWritableLine(), 12);
        foreach ($blocks as &$block) {
            $block = substr($block, 0, -1);
        }

        return implode('', $blocks);
    }

    /**
     * {@inheritdoc}
     */
    public function toWritableLine(Bill $bill): string
    {
        $barcode = $bill->getBarcode();
        $blocks = str_split($barcode, 11);
        $isModule10 = in_array($barcode[2], Convenio::USES_MODULE_10);
        foreach ($blocks as &$block) {
            $block .= $isModule10 ? module10($block) : module11($block);
        }

        return implode('', $blocks);
    }
}
