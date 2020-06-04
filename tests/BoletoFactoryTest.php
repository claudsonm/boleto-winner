<?php

namespace Claudsonm\BoletoWinner\Tests;

use Claudsonm\BoletoWinner\Boleto;
use Claudsonm\BoletoWinner\Factories\BoletoFactory;
use PHPUnit\Framework\TestCase;
use Claudsonm\BoletoWinner\Exceptions\BoletoWinnerException;

class BoletoFactoryTest extends TestCase
{

    /** @test */
    public function it_throws_exception_when_an_empty_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('You must provide a numeric string.');

        (new BoletoFactory())->make('');
    }

    /** @test */
    public function it_throws_exception_when_a_non_numeric_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('You must provide a numeric string.');

        (new BoletoFactory())->make('only text given');
    }

    /** @test */
    public function it_throws_exception_when_an_alpha_numeric_string_is_given()
    {
        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage('The value `123abc456d` given is not a valid barcode or writable line.');

        (new BoletoFactory())->make('123abc456d');
    }

    /** @test */
    public function its_creates_a_boleto_object_from_a_valid_numeric_writable_line()
    {
        $factory = new BoletoFactory();
        $bill = $factory->make('07790001161200000050003967606728182130000200000');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('07791821300002000000001112000000500396760672', $bill->getBarcode());
    }

    /** @test */
    public function it_creates_a_boleto_object_from_a_valid_formatted_writable_line()
    {
        $factory = new BoletoFactory();
        $bill = $factory->make('23793.38128 60023.848041 44000.063303 1 82130000300000');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('23791821300003000003381260023848044400006330', $bill->getBarcode());
    }

    /** @test */
    public function its_creates_a_boleto_object_from_a_valid_barcode()
    {
        $factory = new BoletoFactory();
        $bill = $factory->make('00199000000000969610000003149039000458984217');

        $this->assertInstanceOf(Boleto::class, $bill);
        $this->assertSame('00190000090314903900404589842170900000000096961', $bill->getWritableLine());
    }

    /** @test */
    public function it_throws_exception_when_the_given_writable_line_is_invalid()
    {
        $invalidLine = '99990.00009 01234.567004 00000.001180 7 55650000001050';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidLine}` given is not a valid barcode or writable line.");

        (new BoletoFactory())->make($invalidLine);
    }

    /** @test */
    public function it_throws_exception_when_the_given_barcode_is_invalid()
    {
        $invalidBarcode = '00191556500000010501234560000115451040300999';

        $this->expectException(BoletoWinnerException::class);
        $this->expectExceptionMessage("The value `{$invalidBarcode}` given is not a valid barcode or writable line.");

        (new BoletoFactory())->make($invalidBarcode);
    }

    /** @test */
    public function testa_algo()
    {
        $this->markTestSkipped('WIP');
        $factory = new BoletoFactory();
        $out = $factory->getMyClasses();

        var_dump($out);
    }
}
