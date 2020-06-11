<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Str
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BinaryReader
     */
    public $brBig;

    /**
     * @var BinaryReader
     */
    public $brLittle;

    /**
     * @var Str
     */
    public $string;

    public function setUp()
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->string = new Str();
        $this->brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    public function testStringIsRead()
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->string->read($this->brBig, 5));
        $this->assertEquals('test!', $this->string->read($this->brLittle, 5));
    }

    public function testAlignedStringIsRead()
    {
        $this->brBig->setPosition(6);
        $this->brLittle->setPosition(6);
        $this->brBig->readBits(1);
        $this->brLittle->readBits(1);

        $this->assertEquals('test!', $this->string->readAligned($this->brBig, 5));
        $this->assertEquals('test!', $this->string->readAligned($this->brLittle, 5));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian()
    {
        $this->brBig->readBits(128);
        $this->string->read($this->brBig, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian()
    {
        $this->brLittle->readBits(128);
        $this->string->read($this->brLittle, 1);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidBigEndian()
    {
        $this->string->read($this->brBig, 'foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian()
    {
        $this->string->read($this->brBig, 'foo');
    }
}
