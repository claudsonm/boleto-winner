<?php

namespace Claudsonm\BoletoWinner\Tests;

use BadMethodCallException;
use Claudsonm\BoletoWinner\Bill;
use Claudsonm\BoletoWinner\Boleto;
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

    /** @test */
    public function it_checks_if_the_entry_is_a_valid_writable_line_of_any_bill_type()
    {
        $convenioLine = '84690000001 5 24490082089 9 99993193010 4 99453529299 3';
        $boletoLine = '00190.00009 03149.039004 04589.842170 8 81850000096961';

        $this->assertTrue(BoletoWinner::isValidWritableLine($convenioLine));
        $this->assertTrue(BoletoWinner::isValidWritableLine($boletoLine));

        $invalidConvenioLine = '99990000001 5 24490082089 9 99993193010 4 99453529299 3';
        $invalidBoletoLine = '99990.00009 03149.039004 04589.842170 8 81850000096961';

        $this->assertFalse(BoletoWinner::isValidWritableLine($invalidConvenioLine));
        $this->assertFalse(BoletoWinner::isValidWritableLine($invalidBoletoLine));
    }

    /** @test */
    public function it_checks_if_the_entry_is_a_valid_barcode_of_any_bill_type()
    {
        $convenioBarcode = '82660000000679300418200209414042020102094141';
        $boletoBarcode = '00197556500000010500000001234567000000000118';

        $this->assertTrue(BoletoWinner::isValidBarcode($convenioBarcode));
        $this->assertTrue(BoletoWinner::isValidBarcode($boletoBarcode));

        $invalidConvenioBarcode = '99960000000679300418200209414042020102094141';
        $invalidBoletoBarcode = '99997556500000010500000001234567000000000118';

        $this->assertFalse(BoletoWinner::isValidBarcode($invalidConvenioBarcode));
        $this->assertFalse(BoletoWinner::isValidBarcode($invalidBoletoBarcode));
    }

    /** @test */
    public function it_can_create_a_bill_instance_from_a_barcode_or_writable_line()
    {
        $line = '00190.00009 03149.039004 04589.842170 8 81850000096961';
        $instance = BoletoWinner::makeBill($line);

        $this->assertInstanceOf(Bill::class, $instance);
        $this->assertInstanceOf(Boleto::class, $instance);
    }

    /** @test */
    public function it_gets_the_writable_line_from_the_barcode()
    {
        $barcode = '07791821300002000000001112000000500396760672';

        $this->assertSame(
            '07790001161200000050003967606728182130000200000',
            BoletoWinner::toWritableLine($barcode)
        );
    }

    /** @test */
    public function it_gets_the_barcode_from_the_writable_line()
    {
        $writableLine = '07790.00116 12000.000500 03967.606728 1 82130000200000';

        $this->assertSame(
            '07791821300002000000001112000000500396760672',
            BoletoWinner::toBarcode($writableLine)
        );
    }

    /** @test */
    public function it_check_is_boleto_by_barcode_de_boleto()
    {
        $barcode = '23793946900000291542115092000081970400146930';

        $this->assertTrue(BoletoWinner::isBoletoByBarcode($barcode));
    }

    /** @test */
    public function it_check_is_boleto_by_barcode_de_convenio()
    {
        $barcode = '84660000001249800820899999319301093721978999';

        $this->assertFalse(BoletoWinner::isBoletoByBarcode($barcode));
    }

    /** @test */
    public function it_check_is_boleto_by_writable_line_de_boleto()
    {
        $writableLine = '23792115079200008197304001469305394690000029154';

        $this->assertTrue(BoletoWinner::isBoletoByWritableLine($writableLine));
    }

    /** @test */
    public function it_check_is_boleto_by_writable_line_de_convenio()
    {
        $writableLine = '846600000018249800820899999931930104937219789990';

        $this->assertFalse(BoletoWinner::isBoletoByWritableLine($writableLine));
    }

    /** @test */
    public function it_check_is_convenio_by_barcode_de_convenio()
    {
        $barcode = '84660000001249800820899999319301093721978999';

        $this->assertTrue(BoletoWinner::isConvenioByBarcode($barcode));
    }

    /** @test */
    public function it_check_is_convenio_by_barcode_de_boleto()
    {
        $barcode = '23793946900000291542115092000081970400146930';

        $this->assertFalse(BoletoWinner::isConvenioByBarcode($barcode));
    }

    /** @test */
    public function it_check_is_convenio_by_writable_line_de_convenio()
    {
        $writableLine = '846600000018249800820899999931930104937219789990';

        $this->assertTrue(BoletoWinner::isConvenioByWritableLine($writableLine));
    }

    /** @test */
    public function it_check_is_convenio_by_writable_line_de_boleto()
    {
        $writableLine = '23792115079200008197304001469305394690000029154';

        $this->assertFalse(BoletoWinner::isConvenioByWritableLine($writableLine));
    }

    /** @test */
    public function it_throws_exception_when_the_method_called_do_not_exists()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method `invalidMethod` does not exist.');

        BoletoWinner::invalidMethod();
    }
}
