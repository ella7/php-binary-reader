<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int8
 */
class Int8Test extends TestCase
{
    public BinaryReader $brBig;
    public BinaryReader $brLittle;
    public Int8 $int8;

    public function setUp(): void
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->int8 = new Int8();
        $this->brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::LITTLE);
    }

    public function testUnsignedReaderWithBigEndian(): void
    {
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(3, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(2, $this->int8->read($this->brBig));
        $this->assertEquals(103, $this->int8->read($this->brBig));
        $this->assertEquals(116, $this->int8->read($this->brBig));
        $this->assertEquals(101, $this->int8->read($this->brBig));
        $this->assertEquals(115, $this->int8->read($this->brBig));
        $this->assertEquals(116, $this->int8->read($this->brBig));
        $this->assertEquals(33, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
    }

    public function testSignedReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(-1, $this->int8->readSigned($this->brBig));
        $this->assertEquals(-1, $this->int8->readSigned($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
    }

    public function testReaderWithLittleEndian(): void
    {
        $this->assertEquals(3, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(2, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(103, $this->int8->read($this->brLittle));
        $this->assertEquals(116, $this->int8->read($this->brLittle));
        $this->assertEquals(101, $this->int8->read($this->brLittle));
        $this->assertEquals(115, $this->int8->read($this->brLittle));
        $this->assertEquals(116, $this->int8->read($this->brLittle));
        $this->assertEquals(33, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($this->brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
    }

    public function testBitReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(7, $this->int8->read($this->brBig));
    }

    public function testBitReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(7, $this->int8->read($this->brLittle));
    }

    public function testOutOfBoundsExceptionIsThrownWithBigEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brBig->readBits(128);
        $this->int8->read($this->brBig);
    }

    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brLittle->readBits(128);
        $this->int8->read($this->brLittle);
    }
}
