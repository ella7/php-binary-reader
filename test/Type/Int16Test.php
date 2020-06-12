<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int16
 */
class Int16Test extends TestCase
{
    public BinaryReader $brBig;
    public BinaryReader $brLittle;
    public Int16 $int16;

    public function setUp(): void
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->int16 = new Int16();
        $this->brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::LITTLE);
    }

    public function testUnsignedReaderWithBigEndian(): void
    {
        $this->assertEquals(0, $this->int16->read($this->brBig));
        $this->assertEquals(3, $this->int16->read($this->brBig));
        $this->assertEquals(2, $this->int16->read($this->brBig));
        $this->assertEquals(26484, $this->int16->read($this->brBig));
        $this->assertEquals(25971, $this->int16->read($this->brBig));
        $this->assertEquals(29729, $this->int16->read($this->brBig));
        $this->assertEquals(65535, $this->int16->read($this->brBig));
        $this->assertEquals(65535, $this->int16->read($this->brBig));
    }

    public function testSignedReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($this->brBig));
        $this->assertEquals(65535, $this->int16->read($this->brBig));
    }

    public function testReaderWithLittleEndian(): void
    {
        $this->assertEquals(3, $this->int16->read($this->brLittle));
        $this->assertEquals(0, $this->int16->read($this->brLittle));
        $this->assertEquals(2, $this->int16->read($this->brLittle));
        $this->assertEquals(29799, $this->int16->read($this->brLittle));
        $this->assertEquals(29541, $this->int16->read($this->brLittle));
        $this->assertEquals(8564, $this->int16->read($this->brLittle));
        $this->assertEquals(65535, $this->int16->read($this->brLittle));
        $this->assertEquals(65535, $this->int16->read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($this->brLittle));
        $this->assertEquals(65535, $this->int16->read($this->brLittle));
    }

    public function testBitReaderWithBigEndian(): void
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(1861, $this->int16->read($this->brBig));
    }

    public function testBitReaderWithLittleEndian(): void
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(1876, $this->int16->read($this->brLittle));
    }

    public function testOutOfBoundsExceptionIsThrownWithBigEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brBig->readBits(128);
        $this->int16->read($this->brBig);
    }

    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brLittle->readBits(128);
        $this->int16->read($this->brLittle);
    }

    public function testAlternateMachineByteOrderSigned(): void
    {
        $this->brLittle->setMachineByteOrder(Endian::BIG);
        $this->brLittle->setEndian(Endian::LITTLE);
        $this->assertEquals(3, $this->int16->readSigned($this->brLittle));
    }
}
