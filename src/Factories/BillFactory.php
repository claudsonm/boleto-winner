<?php

namespace Claudsonm\BoletoWinner\Factories;

use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Boleto;

abstract class BillFactory
{
    abstract protected function createBill() : Bill;
}
