<?php

namespace Claudsonm\BoletoWinner\Tests;

use Claudsonm\BoletoWinner\Boleto;
use Claudsonm\BoletoWinner\Convenio;
use Claudsonm\BoletoWinner\Tests\Dummies\DummyBill;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;
use Claudsonm\BoletoWinner\Factories\BillFactory;
use PHPUnit\Framework\TestCase;

class BillFactoryTest extends TestCase
{
    /** @test */
    public function it_checks_that_boleto_and_convenio_are_loaded_by_default_in_the_available_bills()
    {
        $bills = (new BillFactory())->getBills();

        $this->assertTrue(in_array(Boleto::class, $bills));
        $this->assertTrue(in_array(Convenio::class, $bills));
    }

    /** @test */
    public function it_cant_load_classes_that_dont_extends_the_bill_class()
    {
        $class = get_class(new class() {
        });

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The class `{$class}` must be a subclass of `Claudsonm\\BoletoWinner\\Bill`.");

        (new BillFactory())->load($class);
    }

    /** @test */
    public function it_can_load_subclasses_of_bill()
    {
        $factory = new BillFactory();
        $factory->load(DummyBill::class);
        $bills = $factory->getBills();

        $this->assertCount(3, $bills);
        $this->assertTrue(in_array(DummyBill::class, $bills));
    }

    /** @test */
    public function it_doesnt_load_the_same_bill_twice()
    {
        $factory = new BillFactory();
        $factory->load(DummyBill::class);
        $factory->load(DummyBill::class);
        $bills = $factory->getBills();

        $this->assertCount(3, $bills);
        $this->assertTrue(in_array(DummyBill::class, $bills));
    }

    /** @test */
    public function it_throws_exception_when_an_empty_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('You must provide a numeric string.');

        (new BillFactory())->make('');
    }

    /** @test */
    public function it_throws_exception_when_a_non_numeric_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('You must provide a numeric string.');

        (new BillFactory())->make('only text given');
    }

    /** @test */
    public function it_throws_exception_when_an_alpha_numeric_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('The value `123abc456d` given is not a valid barcode or writable line.');

        (new BillFactory())->make('123abc456d');
    }

    /** @test */
    public function its_creates_a_boleto_object_from_a_valid_numeric_writable_line()
    {
        $factory = new BillFactory();
        $bill = $factory->make('07790001161200000050003967606728182130000200000');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('07791821300002000000001112000000500396760672', $bill->getBarcode());
    }

    /** @test */
    public function its_creates_a_convenio_object_from_a_valid_numeric_writable_line()
    {
        $factory = new BillFactory();
        $bill = $factory->make('846200000012379800820894999931930112030367115992');

        $this->assertInstanceOf(Convenio::class, $bill);
        $this->assertSame('84620000001379800820899999319301103036711599', $bill->getBarcode());
    }

    /** @test */
    public function it_creates_a_boleto_object_from_a_valid_formatted_writable_line()
    {
        $factory = new BillFactory();
        $bill = $factory->make('23793.38128 60023.848041 44000.063303 1 82130000300000');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('23791821300003000003381260023848044400006330', $bill->getBarcode());
    }

    /** @test */
    public function it_creates_a_convenio_object_from_a_valid_formatted_writable_line()
    {
        $factory = new BillFactory();
        $bill = $factory->make('84690000001 5 24490082089 9 99993193010 4 99453529299 3');

        $this->assertInstanceOf(Convenio::class, $bill);
        $this->assertSame('84690000001244900820899999319301099453529299', $bill->getBarcode());
    }

    /** @test */
    public function its_creates_a_boleto_object_from_a_valid_barcode()
    {
        $factory = new BillFactory();
        $bill = $factory->make('00199000000000969610000003149039000458984217');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('00190000090314903900404589842170900000000096961', $bill->getWritableLine());
    }

    /** @test */
    public function its_creates_a_convenio_object_from_a_valid_barcode()
    {
        $factory = new BillFactory();
        $bill = $factory->make('84660000001249800820899999319301093721978999');

        $this->assertInstanceOf(Convenio::class, $bill);
        $this->assertSame('846600000018249800820899999931930104937219789990', $bill->getWritableLine());
    }

    /** @test */
    public function it_throws_exception_when_the_given_boleto_writable_line_is_invalid()
    {
        $invalidLine = '99990.00009 01234.567004 00000.001180 7 55650000001050';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidLine}` given is not a valid barcode or writable line.");

        (new BillFactory())->make($invalidLine);
    }

    /** @test */
    public function it_throws_exception_when_the_given_convenio_writable_line_is_invalid()
    {
        $invalidLine = '84630000001 1 24980082089 5 99993193010 4 91789116299 7';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidLine}` given is not a valid barcode or writable line.");

        (new BillFactory())->make($invalidLine);
    }

    /** @test */
    public function it_throws_exception_when_the_given_boleto_barcode_is_invalid()
    {
        $invalidBarcode = '00191556500000010501234560000115451040300999';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidBarcode}` given is not a valid barcode or writable line.");

        (new BillFactory())->make($invalidBarcode);
    }

    /** @test */
    public function it_throws_exception_when_the_given_convenio_barcode_is_invalid()
    {
        $invalidBarcode = '82660000000679300418200209414042020102094199';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidBarcode}` given is not a valid barcode or writable line.");

        (new BillFactory())->make($invalidBarcode);
    }
}