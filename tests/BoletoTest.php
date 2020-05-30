<?php

namespace Claudsonm\BoletoWinner\Tests;

use PHPUnit\Framework\TestCase;
use Claudsonm\BoletoWinner\Boleto;

class BoletoTest extends TestCase
{
    /** @test */
    public function it_can_set_the_properties_using_the_fluent_api()
    {
        $boleto = (new Boleto())->setWritableLine('132')
            ->setBarcode('4444');

        $this->assertSame('132', $boleto->getWritableLine());
        $this->assertSame('4444', $boleto->getBarcode());
    }

    /** @test */
    public function it_can_infer_the_writable_line_from_the_barcode()
    {
        $boleto = (new Boleto())->setBarcode('42292686100000546597115000001954416002003452');

        $this->assertSame('42297115040000195441160020034520268610000054659', $boleto->getWritableLine());
    }

    /** @test */
    public function it_can_infer_the_barcode_from_the_writable_line()
    {
        $boleto = (new Boleto())->setWritableLine('42297115040000195441160020034520268610000054659');

        $this->assertSame('42292686100000546597115000001954416002003452', $boleto->getBarcode());
    }
}
