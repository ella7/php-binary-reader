<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Byte
 */
class ByteTest extends AbstractTestCase
{
    public Byte $byte;

    public function setUp(): void
    {
        $this->byte = new Byte();
    }

    /** @dataProvider binaryReaders */
    public function testBytesAreRead(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $this->byte->read($brBig, 5));
        $this->assertEquals('test!', $this->byte->read($brLittle, 5));
    }

    /** @dataProvider largeReaders */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->byte->read($brBig, 1);
    }

    /** @dataProvider littleReaders */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->byte->read($brLittle, 1);
    }

    /** @dataProvider largeReaders */
    public function testExceptionIsThrownIfNullBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(InvalidDataException::class);

        $brBig->readBits(360);
        $this->byte->read($brBig, null);
    }

    /** @dataProvider littleReaders */
    public function testExceptionIsThrownIfNullLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(InvalidDataException::class);

        $brLittle->readBits(360);
        $this->byte->read($brLittle, null);
    }
}
