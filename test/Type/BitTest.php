<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Bit
 */
class BitTest extends AbstractTestCase
{
    public Bit $bit;

    public function setUp(): void
    {
        $this->bit = new Bit();
    }

    /** @dataProvider binaryReaders */
    public function testUnsignedBitReader(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $this->assertEquals(3, $brBig->readUBits(32));
        $this->assertEquals(3, $brLittle->readUBits(32));
        $this->assertEquals(2, $brBig->readUBits(16));
        $this->assertEquals(2, $brLittle->readUBits(16));
        $this->assertEquals(103, $brBig->readUBits(8));
        $this->assertEquals(103, $brLittle->readUBits(8));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $brBig->readUBits(28);
        $brLittle->readUBits(28);

        $this->assertEquals(0, $brBig->readUBits(6));
        $this->assertEquals(2, $brLittle->readUBits(6));
        $this->assertEquals(0, $brBig->readUBits(4));
        $this->assertEquals(0, $brLittle->readUBits(4));
        $this->assertEquals(0, $brBig->readUBits(2));
        $this->assertEquals(0, $brLittle->readUBits(2));

        $brBig->readUBits(80);
        $brLittle->readUBits(80);

        $this->assertEquals(0xF, $brBig->readUBits(4));
        $this->assertEquals(0xF, $brLittle->readUBits(4));
        $this->assertEquals(3, $brBig->readUBits(2));
        $this->assertEquals(3, $brLittle->readUBits(2));
    }

    /** @dataProvider binaryReaders */
    public function testSignedBitReader(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $this->assertEquals(50331648, $brBig->readBits(32));
        $this->assertEquals(3, $brLittle->readBits(32));
        $this->assertEquals(512, $brBig->readBits(16));
        $this->assertEquals(2, $brLittle->readBits(16));
        $this->assertEquals(103, $brBig->readBits(8));
        $this->assertEquals(103, $brLittle->readBits(8));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $brBig->readBits(28);
        $brLittle->readBits(28);

        $this->assertEquals(0, $brBig->readBits(6));
        $this->assertEquals(2, $brLittle->readBits(6));
        $this->assertEquals(0, $brBig->readBits(4));
        $this->assertEquals(0, $brLittle->readBits(4));
        $this->assertEquals(0, $brBig->readBits(2));
        $this->assertEquals(0, $brLittle->readBits(2));
    }

    /** @dataProvider largeReaders */
    public function testExceptionBitsBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $brBig->setPosition(45);
        $brBig->readBits(16);
    }

    /** @dataProvider littleReaders */
    public function testExceptionBitsLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $brLittle->setPosition(45);
        $brLittle->readBits(16);
    }

    /** @dataProvider largeReaders */
    public function testExceptionBitsOnLastBitsBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->setPosition(44);
        $brBig->readBits(4);
        $brBig->readBits(2);
        $brBig->readBits(2);
        $brBig->readBits(1);
    }

    /** @dataProvider littleReaders */
    public function testExceptionBitsOnLastBitsLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->setPosition(44);
        $brLittle->readBits(4);
        $brLittle->readBits(2);
        $brLittle->readBits(2);
        $brLittle->readBits(1);
    }

    /** @dataProvider littleReaders */
    public function testExceptionOnReadingNullLengthLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(InvalidDataException::class);
        $this->bit->read($brLittle, null);
    }

    /** @dataProvider largeReaders */
    public function testExceptionOnReadingNullLengthLargeEndian(BinaryReader $brBig): void
    {
        $this->expectException(InvalidDataException::class);
        $this->bit->read($brBig, null);
    }
}
