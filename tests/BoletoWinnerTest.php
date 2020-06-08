<?php

namespace Claudsonm\BoletoWinner\Tests;

use Claudsonm\BoletoWinner\BoletoWinner;
use PHPUnit\Framework\TestCase;

class BoletoWinnerTest extends TestCase
{
    /** @test */
    public function it_checks_when_a_bar_code_is_invalid()
    {
        $barcode = '2213';

        $this->assertTrue(BoletoWinner::isValid($barcode));
    }

    /** @test */
    public function it_checks_when_a_writable_line_is_invalid()
    {
        $barcode = '2213';

        $this->assertTrue(BoletoWinner::isValid($barcode));
    }
}
