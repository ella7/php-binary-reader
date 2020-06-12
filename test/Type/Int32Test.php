<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int32
 */
class Int32Test extends TestCase
{
    public BinaryReader $brBig;
    public BinaryReader $brLittle;
    public Int32 $int32;

    public function setUp(): void
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->int32 = new Int32();
        $this->brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::LITTLE);
    }

    public function testUnsignedReaderWithBigEndian(): void
    {
        $this->assertEquals(3, $this->int32->read($this->brBig));
        $this->assertEquals(157556, $this->int32->read($this->brBig));
        $this->assertEquals(1702065185, $this->int32->read($this->brBig));
        $this->assertEquals(4294967295, $this->int32->read($this->brBig));
    }

    public function testSignedReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($this->brBig));
    }

    public function testReaderWithLittleEndian(): void
    {
        $this->assertEquals(3, $this->int32->read($this->brLittle));
        $this->assertEquals(1952907266, $this->int32->read($this->brLittle));
        $this->assertEquals(561279845, $this->int32->read($this->brLittle));
        $this->assertEquals(4294967295, $this->int32->read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($this->brLittle));
    }

    public function testBitReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(122050356, $this->int32->read($this->brBig));
    }

    public function testBitReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(122107476, $this->int32->read($this->brLittle));
    }

    public function testOutOfBoundsExceptionIsThrownWithBigEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brBig->readBits(128);
        $this->int32->read($this->brBig);
    }

    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brLittle->readBits(128);
        $this->int32->read($this->brLittle);
    }

    public function testAlternateMachineByteOrderSigned(): void
    {
        $this->brLittle->setMachineByteOrder(Endian::BIG);
        $this->brLittle->setEndian(Endian::LITTLE);
        $this->assertEquals(3, $this->int32->readSigned($this->brLittle));
    }
}
