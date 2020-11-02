<?php

namespace Claudsonm\BoletoWinner\Factories;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Boleto;
use Claudsonm\BoletoWinner\Convenio;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;

class BillFactory
{
    /**
     * Bills supported.
     *
     * @var array|string[]
     */
    protected array $bills = [
        'boleto' => Boleto::class,
        'convenio' => Convenio::class,
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
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new static();

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
     * Register a bill with its type and class name.
     *
     * @throws BoletoWinnerException
     */
    public function register(string $type, string $class): void
    {
        if (! is_a($class, Bill::class, true)) {
            throw BoletoWinnerException::invalidBillClass($class);
        }

        $this->bills[$type] = $class;
    }

    /**
     * Removes a bill from the supported classes.
     */
    public function unregister(string $type): void
    {
        if (isset($this->bills[$type])) {
            unset($this->bills[$type]);
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
