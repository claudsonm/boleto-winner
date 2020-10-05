<?php

namespace Claudsonm\BoletoWinner\Factories;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;

class BillFactory
{
    /**
     * Bills available to be constructed. New classes can be added dynamically
     * using the `load` method.
     *
     * @var array|string[]
     */
    protected array $bills = [
        'Claudsonm\BoletoWinner\Boleto',
        'Claudsonm\BoletoWinner\Convenio',
    ];

    /**
     * The singleton instance.
     */
    private static ?BillFactory $instance = null;

    protected function __construct()
    {
        // Preventing direct construction calls with the `new` operator.
    }

    /**
     * Get the singleton class instance or creates one if it's not set yet.
     */
    public static function getInstance(): self
    {
        if (! self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Get the available bills.
     *
     * @var array|string[]
     */
    public function getBills(): array
    {
        return $this->bills;
    }

    /**
     * @throws BoletoWinnerException
     */
    public function load(string $class): void
    {
        if (! is_subclass_of($class, Bill::class)) {
            throw BoletoWinnerException::invalidBillClass($class);
        }

        if (! in_array($class, $this->bills)) {
            $this->bills[] = $class;
        }
    }

    /**
     * @throws BoletoWinnerException
     */
    public function make(string $barcodeOrWritableLine): Bill
    {
        $input = preg_replace('/[^0-9]/', '', $barcodeOrWritableLine);
        if (empty($input)) {
            throw BoletoWinnerException::inputRequired();
        }

        foreach ($this->getBills() as $bill) {
            $bill = new $bill();
            $bill->setBarcode($input);
            if ($bill->isBarcodeValid()) {
                return $bill;
            }

            $bill = new $bill();
            $bill->setWritableLine($input);
            if ($bill->isWritableLineValid()) {
                return $bill;
            }
        }

        throw BoletoWinnerException::invalidInput($barcodeOrWritableLine);
    }
}
