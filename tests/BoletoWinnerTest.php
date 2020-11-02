<?php

namespace Claudsonm\BoletoWinner\Tests;

use Claudsonm\BoletoWinner\BoletoWinner;
use PHPUnit\Framework\TestCase;

class BoletoWinnerTest extends TestCase
{
    /** @test */
    public function it_checks_when_a_boleto_barcode_is_invalid()
    {
        $barcode = '99991821300003000003381260023848044400006330';

        $this->assertFalse(BoletoWinner::isValid($barcode));
    }

    /** @test */
    public function it_checks_when_a_boleto_writable_line_is_invalid()
    {
        $line = '34191.79001 01043.510047 99990.150008 1 84270026800';

        $this->assertFalse(BoletoWinner::isValid($line));
    }

    /** @test */
    public function it_checks_when_a_boleto_barcode_is_valid()
    {
        $barcode = '23791821300003000003381260023848044400006330';

        $this->assertTrue(BoletoWinner::isValid($barcode));
    }

    /** @test */
    public function it_checks_when_a_boleto_writable_line_is_valid()
    {
        $line = '34191.79001 01043.510047 91020.150008 1 84270026000';

        $this->assertTrue(BoletoWinner::isValid($line));
    }

    /** @test */
    public function it_checks_when_a_convenio_barcode_is_invalid()
    {
        $barcode = '84630000001249800820899999319301091789119999';

        $this->assertFalse(BoletoWinner::isValid($barcode));
    }

    /** @test */
    public function it_checks_when_a_convenio_writable_line_is_invalid()
    {
        $line = '84630000001 1 24980082089 9 99993193010 4 91789116299 9';

        $this->assertFalse(BoletoWinner::isValid($line));
    }

    /** @test */
    public function it_checks_when_a_convenio_barcode_is_valid()
    {
        $barcode = '84630000001249800820899999319301091789116299';

        $this->assertTrue(BoletoWinner::isValid($barcode));
    }

    /** @test */
    public function it_checks_when_a_convenio_writable_line_is_valid()
    {
        $line = '84630000001 1 24980082089 9 99993193010 4 91789116299 7';

        $this->assertTrue(BoletoWinner::isValid($line));
    }

    /** @test */
    public function it_checks_if_the_given_barcode_represents_the_desired_bill_type()
    {
        $barcode = '23791821300003000003381260023848044400006330';

        $this->assertTrue(BoletoWinner::isValidBoleto($barcode));
        $this->assertFalse(BoletoWinner::isValidConvenio($barcode));
    }

    /** @test */
    public function it_checks_if_the_given_writable_line_represents_the_desired_bill_type()
    {
        $line = '84690000001 5 24490082089 9 99993193010 4 99453529299 3';

        $this->assertFalse(BoletoWinner::isValidBoleto($line));
        $this->assertTrue(BoletoWinner::isValidConvenio($line));
    }
}
