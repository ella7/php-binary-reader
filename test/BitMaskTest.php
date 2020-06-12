<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpBinaryReader\BitMask
 */
class BitMaskTest extends TestCase
{
    public function testBitMaskArray(): void
    {
        $expected = [
            [0x00, 0xFF],
            [0x01, 0x7F],
            [0x03, 0x3F],
            [0x07, 0x1F],
            [0x0F, 0x0F],
            [0x1F, 0x07],
            [0x3F, 0x03],
            [0x7F, 0x01],
            [0xFF, 0x00]
        ];

        $bitmask = new Bitmask();
        $this->assertEquals($expected, $bitmask->getBitMasks());
    }

    public function testLoMaskIsReturnedByBit(): void
    {
        $bitmask = new Bitmask();
        $this->assertEquals(0x03, $bitmask->getMask(2, BitMask::MASK_LO));
        $this->assertEquals(0xFF, $bitmask->getMask(8, BitMask::MASK_LO));
    }

    public function testHiMaskIsReturnedByBit(): void
    {
        $bitmask = new Bitmask();
        $this->assertEquals(0x03, $bitmask->getMask(6, BitMask::MASK_HI));
        $this->assertEquals(0xFF, $bitmask->getMask(0, BitMask::MASK_HI));
    }

    public function testExceptionIsThrownIfMaskTypeIsUnsupported(): void
    {
        $this->expectException(InvalidDataException::class);
        $bitmask = new Bitmask();
        $bitmask->getMask(5, 5);
    }
}
