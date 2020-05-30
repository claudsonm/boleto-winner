<?php

namespace Claudsonm\BoletoWinner\Tests;

use PHPUnit\Framework\TestCase;
use Claudsonm\BoletoWinner\Boleto;

class BoletoTest extends TestCase
{
    protected array $validBoletos;

    /** @test */
    public function it_can_set_the_properties_using_the_fluent_api()
    {
        $boleto = (new Boleto())->setWritableLine('132')
            ->setBarcode('4444');

        $this->assertSame('132', $boleto->getWritableLine());
        $this->assertSame('4444', $boleto->getBarcode());
    }

    /**
     * @test
     * @dataProvider collectionOfValidBoletos
     */
    public function it_can_infer_the_writable_line_from_the_barcode(string $writableLine, string $barcode)
    {
        $boleto = (new Boleto())->setBarcode($barcode);

        $this->assertSame($writableLine, $boleto->getWritableLine());
    }

    /**
     * @test
     * @dataProvider collectionOfValidBoletos
     */
    public function it_can_infer_the_barcode_from_the_writable_line(string $writableLine, string $barcode)
    {
        $boleto = (new Boleto())->setWritableLine($writableLine);

        $this->assertSame($barcode, $boleto->getBarcode());
    }

    /**
     * @test
     * @dataProvider collectionOfValidBoletos
     */
    public function it_can_verify_when_the_writable_line_is_valid(string $writableLine)
    {
        $boleto = (new Boleto())->setWritableLine($writableLine);

        $this->assertTrue($boleto->isWritableLineValid());
    }

    /** @test */
    public function it_can_verify_when_the_writable_line_is_invalid()
    {
        // TODO create a collection of invalid writable lines
        $boleto = (new Boleto())->setWritableLine('00191234058888811545110403005183555650000001050');

        $this->assertFalse($boleto->isWritableLineValid());
    }

    /**
     * @test
     * @dataProvider collectionOfValidBoletos
     */
    public function it_can_verify_when_the_barcode_is_valid(string $writableLine, string $barcode)
    {
        $boleto = (new Boleto())->setBarcode($barcode);

        $this->assertTrue($boleto->isBarcodeValid());
    }

    /** @test */
    public function it_can_verify_when_the_barcode_is_invalid()
    {
        // TODO create a collection of invalid barcodes
        $boleto = (new Boleto())->setBarcode('00195556509999910501234000000115451040300518');

        $this->assertFalse($boleto->isBarcodeValid());
    }

    public function collectionOfValidBoletos()
    {
        if (empty($this->validBoletos)) {
            $file = file_get_contents(__DIR__.'/Fixtures/boletos/valid_writable_lines_and_barcodes.json');
            $boletos = json_decode($file, true);

            $content = [];
            foreach ($boletos as $boleto) {
                $line = $boleto['writable_line'];
                $line = str_replace(['.', ' '], '', $line);
                $content[] = [$line, $boleto['barcode']];
            }
            $this->validBoletos = $content;
        }

        return $this->validBoletos;
    }
}
