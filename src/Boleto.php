<?php

namespace Claudsonm\BoletoWinner;

use Claudsonm\BoletoWinner\Converters\BoletoConverter;
use Claudsonm\BoletoWinner\Converters\Converter;
use Claudsonm\BoletoWinner\Validators\BoletoValidator;
use Claudsonm\BoletoWinner\Validators\Validator;

class Boleto extends Bill
{
    /**
     * {@inheritdoc}
     */
    protected function useConverter(): Converter
    {
        return new BoletoConverter();
    }

    /**
     * {@inheritdoc}
     */
    protected function useValidator(): Validator
    {
        return new BoletoValidator();
    }
}
