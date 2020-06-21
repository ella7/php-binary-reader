<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int64
 */
class Int64Test extends AbstractTestCase
{
    public Int64 $int64;

    public function setUp(): void
    {
        $this->int64 = new Int64();
    }

    /** @dataProvider largeReaders */
    public function testUnsignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $this->assertEquals(12885059444, $this->int64->read($brBig));
        $this->assertEquals(7310314309530157055, $this->int64->read($brBig));
    }

    /** @dataProvider largeReaders */
    public function testSignedReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(12);
        $this->assertEquals(-3229614080, $this->int64->readSigned($brBig));
    }

    /** @dataProvider littleReaders */
    public function testReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->assertEquals(8387672839590772739, $this->int64->read($brLittle));
        $this->assertEquals(18446744069975864165, $this->int64->read($brLittle));
    }

    /** @dataProvider littleReaders */
    public function testSignedReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(12);
        $this->assertEquals(4575657225703391231, $this->int64->readSigned($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testBitReaderWithBigEndian(BinaryReader $brBig): void
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(504403158265495567, $this->int64->read($brBig));
    }

    /** @dataProvider littleReaders */
    public function testBitReaderWithLittleEndian(BinaryReader $brLittle): void
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(504403158265495567, $this->int64->read($brLittle));
    }

    /** @dataProvider largeReaders */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brBig->readBits(360);
        $this->int64->read($brBig);
    }

    /** @dataProvider binaryReaders */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brLittle): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $brLittle->readBits(360);
        $this->int64->read($brLittle);
    }

    /** @dataProvider littleReaders */
    public function testAlternateMachineByteOrderSigned(BinaryReader $brLittle): void
    {
        $brLittle->setMachineByteOrder(Endian::BIG);
        $brLittle->setEndian(Endian::LITTLE);
        $this->assertEquals(8387672839590772739, $this->int64->readSigned($brLittle));
    }

    public function testEndian(): void
    {
        $this->int64->endianBig = 'X';
        $this->assertEquals('X', $this->int64->endianBig);

        $this->int64->endianLittle = 'Y';
        $this->assertEquals('Y', $this->int64->endianLittle);
    }
}
