<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;
use PhpBinaryReader\Exception\InvalidDataException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Byte
 */
class ByteTest extends TestCase
{
    public BinaryReader $brBig;
    public BinaryReader $brLittle;
    public Byte $byte;

    public function setUp(): void
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->byte = new Byte();
        $this->brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::LITTLE);
    }

    public function testBytesAreRead(): void
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->byte->read($this->brBig, 5));
        $this->assertEquals('test!', $this->byte->read($this->brLittle, 5));
    }

    public function testExceptionIsThrownIfOutOfBoundsBigEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brBig->readBits(128);
        $this->byte->read($this->brBig, 1);
    }

    public function testExceptionIsThrownIfOutOfBoundsLittleEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brLittle->readBits(128);
        $this->byte->read($this->brLittle, 1);
    }

    public function testExceptionIsThrownIfEmptyIsPassed(): void
    {
        $this->expectException(InvalidDataException::class);

        $this->brLittle->readBits(128);
        $this->byte->read($this->brLittle, null);
    }
}
