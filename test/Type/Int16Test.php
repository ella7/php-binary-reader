<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int16
 */
class Int16Test extends AbstractTestCase
{
    public Int16 $int16;

    public function setUp(): void
    {
        $this->int16 = new Int16();
    }

    /** @dataProvider largeReaders */
    public function testUnsignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $this->assertEquals(0, $this->int16->read($brBig));
        $this->assertEquals(3, $this->int16->read($brBig));
        $this->assertEquals(2, $this->int16->read($brBig));
        $this->assertEquals(26484, $this->int16->read($brBig));
        $this->assertEquals(25971, $this->int16->read($brBig));
        $this->assertEquals(29729, $this->int16->read($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
    }

    /** @dataProvider largeReaders */
    public function testSignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->assertEquals(3, $this->int16->read($brLittle));
        $this->assertEquals(0, $this->int16->read($brLittle));
        $this->assertEquals(2, $this->int16->read($brLittle));
        $this->assertEquals(29799, $this->int16->read($brLittle));
        $this->assertEquals(29541, $this->int16->read($brLittle));
        $this->assertEquals(8564, $this->int16->read($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
    }

    /** @dataProvider littleReaders */
    public function testSignedReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testBitReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(1861, $this->int16->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testBitReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(1876, $this->int16->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->int16->read($brBig);
    }

    /** @dataProvider littleReaders */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brLittle)
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->int16->read($brLittle);
    }

    /** @dataProvider littleReaders */
    public function testAlternateMachineByteOrderSigned(BinaryReader $brLittle): void
    {
        $brLittle->setMachineByteOrder(Endian::BIG);
        $brLittle->setEndian(Endian::LITTLE);
        $this->assertEquals(3, $this->int16->readSigned($brLittle));
    }

    public function testEndian(): void
    {
        $this->int16->endianBig = 'X';
        $this->assertEquals('X', $this->int16->endianBig);

        $this->int16->endianLittle = 'Y';
        $this->assertEquals('Y', $this->int16->endianLittle);
    }
}
