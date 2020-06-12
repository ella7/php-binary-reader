<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\Endian
 */
class EndianTest extends TestCase
{
    public function testConstants()
    {
        $this->assertEquals(1, Endian::BIG);
        $this->assertEquals(2, Endian::LITTLE);
        $this->assertEquals(1, Endian::BIG);
        $this->assertEquals(2, Endian::LITTLE);
    }

    public function testConvertDoesNothingIfSingleDigit()
    {
        $endian = new Endian();
        $this->assertEquals(9, $endian->Convert(9));
    }

    public function testConvert()
    {
        $endian = new Endian();
        $this->assertEquals(128, $endian->convert(2147483648));
    }
}
