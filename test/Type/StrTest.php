<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Str
 */
class StrTest extends AbstractTestCase
{
    public Str $string;

    public function setUp(): void
    {
        $this->string = new Str();
    }

    /** @dataProvider binaryReaders */
    public function testStringIsRead(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $this->string->read($brBig, 5));
        $this->assertEquals('test!', $this->string->read($brLittle, 5));
    }

    /** @dataProvider binaryReaders */
    public function testAlignedStringIsRead(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $brBig->setPosition(6);
        $brLittle->setPosition(6);
        $brBig->readBits(1);
        $brLittle->readBits(1);

        $this->assertEquals('test!', $this->string->readAligned($brBig, 5));
        $this->assertEquals('test!', $this->string->readAligned($brLittle, 5));
    }

    /** @dataProvider largeReaders */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->string->read($brBig, 1);
    }

    /** @dataProvider littleReaders */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->string->read($brLittle, 1);
    }
}
