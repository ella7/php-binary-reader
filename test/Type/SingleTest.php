<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Single
 */
class SingleTest extends AbstractTestCase
{
    public Single $single;

    public function setUp(): void
    {
        $this->single = new Single();
    }

    /** @dataProvider binaryReaders */
    public function testReaderWithBothEndian(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        foreach ([$brBig, $brLittle] as $brReader) {
            $brReader->setPosition(16);
            $this->assertEquals(1.0, $this->single->read($brReader));
            $this->assertEquals(-1.0, $this->single->read($brReader));
            $this->assertTrue(is_nan($this->single->read($brReader)));
            $this->assertSame(INF, $this->single->read($brReader));
            $this->assertSame(-INF, $this->single->read($brReader));
        }
    }

    /** @dataProvider largeReaders */
    public function testBitReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(36);
        $brBig->readBits(1);
        $this->assertSame(1.0, $this->single->read($brBig));
        $this->assertSame(-1.0, $this->single->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testBitReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(36);
        $brLittle->readBits(1);
        $this->assertSame(1.0, $this->single->read($brLittle));
        $this->assertSame(-1.0, $this->single->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->single->read($brBig);
    }

    /** @dataProvider littleReaders */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->single->read($brLittle);
    }
}
