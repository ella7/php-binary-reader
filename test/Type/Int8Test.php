<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int8
 */
class Int8Test extends AbstractTestCase
{
    public Int8 $int8;

    public function setUp(): void
    {
        $this->int8 = new Int8();
    }

    /** @dataProvider largeReaders */
    public function testUnsignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(3, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(2, $this->int8->read($brBig));
        $this->assertEquals(103, $this->int8->read($brBig));
        $this->assertEquals(116, $this->int8->read($brBig));
        $this->assertEquals(101, $this->int8->read($brBig));
        $this->assertEquals(115, $this->int8->read($brBig));
        $this->assertEquals(116, $this->int8->read($brBig));
        $this->assertEquals(33, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
    }

    /** @dataProvider largeReaders */
    public function testSignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(12);
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(-1, $this->int8->readSigned($brBig));
        $this->assertEquals(-1, $this->int8->readSigned($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->assertEquals(3, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(2, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(103, $this->int8->read($brLittle));
        $this->assertEquals(116, $this->int8->read($brLittle));
        $this->assertEquals(101, $this->int8->read($brLittle));
        $this->assertEquals(115, $this->int8->read($brLittle));
        $this->assertEquals(116, $this->int8->read($brLittle));
        $this->assertEquals(33, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
    }

    /** @dataProvider littleReaders */
    public function testSignedReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(12);
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testBitReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(7, $this->int8->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testBitReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(7, $this->int8->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->int8->read($brBig);
    }

    /** @dataProvider binaryReaders */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->int8->read($brLittle);
    }

    public function testEndian(): void
    {
        $this->int8->endian = 'X';
        $this->assertEquals('X', $this->int8->endian);
    }
}
