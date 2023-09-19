<?php

namespace Claudsonm\BoletoWinner;

use Claudsonm\BoletoWinner\Converters\Converter;
use Claudsonm\BoletoWinner\Validators\Validator;

abstract class Bill
{
    protected string $writableLine;

    protected string $barcode;

    protected Validator $validator;

    protected Converter $converter;

    public function __construct()
    {
        $this->converter = $this->useConverter();
        $this->validator = $this->useValidator();
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function setWritableLine(string $writableLine): self
    {
        $this->writableLine = $writableLine;

        return $this;
    }

    public function getWritableLine(): string
    {
        if (empty($this->writableLine)) {
            $this->barcodeToWritableLine();
        }

        return $this->writableLine;
    }

    public function getBarcode(): string
    {
        if (empty($this->barcode)) {
            $this->writableLineToBarcode();
        }

        return $this->barcode;
    }

    /**
     * Determines via the delegated validator if the writable line is valid.
     */
    public function isWritableLineValid(): bool
    {
        return $this->validator->verifyWritableLine($this->writableLine);
    }

    /**
     * Determines via the delegated validator if the barcode is valid.
     */
    public function isBarcodeValid(): bool
    {
        return $this->validator->verifyBarcode($this->barcode);
    }

    /**
     * Fills the writable line property with the output of the delegated
     * converter strategy class.
     */
    protected function barcodeToWritableLine(): void
    {
        $this->setWritableLine($this->converter->toWritableLine($this));
    }

    /**
     * Fills the barcode property with the output of the delegated converter
     * strategy class.
     */
    protected function writableLineToBarcode(): void
    {
        $this->setBarcode($this->converter->toBarcode($this));
    }

    /**
     * Defines which converter strategy class is used with the bill instance.
     */
    abstract protected function useConverter(): Converter;

    /**
     * Defines which validator strategy class is used with the bill instance.
     */
    abstract protected function useValidator(): Validator;
}
