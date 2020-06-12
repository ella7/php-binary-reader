<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;
use PhpBinaryReader\Exception\InvalidDataException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Str
 */
class StringTest extends TestCase
{
    public BinaryReader $brBig;
    public BinaryReader $brLittle;
    public Str $string;

    public function setUp(): void
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->string = new Str();
        $this->brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::LITTLE);
    }

    public function testStringIsRead(): void
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->string->read($this->brBig, 5));
        $this->assertEquals('test!', $this->string->read($this->brLittle, 5));
    }

    public function testAlignedStringIsRead(): void
    {
        $this->brBig->setPosition(6);
        $this->brLittle->setPosition(6);
        $this->brBig->readBits(1);
        $this->brLittle->readBits(1);

        $this->assertEquals('test!', $this->string->readAligned($this->brBig, 5));
        $this->assertEquals('test!', $this->string->readAligned($this->brLittle, 5));
    }

    public function testExceptionIsThrownIfOutOfBoundsBigEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brBig->readBits(128);
        $this->string->read($this->brBig, 1);
    }

    public function testExceptionIsThrownIfOutOfBoundsLittleEndian(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->brLittle->readBits(128);
        $this->string->read($this->brLittle, 1);
    }

    public function testExceptionIsThrownIfLengthIsInvalidBigEndian(): void
    {
        $this->expectException(InvalidDataException::class);

        $this->string->read($this->brBig, null);
    }

    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian(): void
    {
        $this->expectException(InvalidDataException::class);

        $this->string->read($this->brBig, null);
    }
}
