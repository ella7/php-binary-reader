<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;

/**
 * @coversDefaultClass \PhpBinaryReader\BinaryReader
 */
class BinaryReaderTest extends AbstractTestCase
{
    /** @dataProvider binaryReaders */
    public function testEof($brBig, $brLittle): void
    {
        $brBig->setPosition(44);
        $this->assertFalse($brBig->isEof());
        $brBig->setPosition(45);
        $this->assertTrue($brBig->isEof());

        $brLittle->setPosition(44);
        $this->assertFalse($brLittle->isEof());
        $brLittle->setPosition(45);
        $this->assertTrue($brLittle->isEof());
    }

    /** @dataProvider binaryReaders */
    public function testCanReadBytes($brBig, $brLittle): void
    {
        $brBig->setPosition(44);
        $this->assertTrue($brBig->canReadBytes());
        $this->assertTrue($brBig->canReadBytes(1));
        $this->assertFalse($brBig->canReadBytes(2));

        $brLittle->setPosition(44);
        $this->assertTrue($brLittle->canReadBytes());
        $this->assertTrue($brLittle->canReadBytes(1));
        $this->assertFalse($brLittle->canReadBytes(2));
    }

    /** @dataProvider binaryReaders */
    public function testBitReader($brBig, $brLittle): void
    {
        $this->assertEquals(50331648, $brBig->readBits(32));
        $this->assertEquals(3, $brLittle->readBits(32));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $this->assertEquals(3, $brBig->readUBits(32));
        $this->assertEquals(3, $brLittle->readUBits(32));
    }

    /** @dataProvider binaryReaders */
    public function testInt8($brBig, $brLittle): void
    {
        $brLittle->setPosition(6);
        $brBig->setPosition(6);

        $this->assertEquals(103, $brBig->readInt8());
        $this->assertEquals(103, $brLittle->readInt8());

        $brLittle->setPosition(6);
        $brBig->setPosition(6);

        $this->assertEquals(103, $brBig->readUInt8());
        $this->assertEquals(103, $brLittle->readUInt8());
    }

    /** @dataProvider binaryReaders */
    public function testInt16(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $brLittle->setPosition(4);
        $brBig->setPosition(4);

        $this->assertEquals(512, $brBig->readInt16());
        $this->assertEquals(2, $brLittle->readInt16());

        $brLittle->setPosition(4);
        $brBig->setPosition(4);

        $this->assertEquals(2, $brBig->readUInt16());
        $this->assertEquals(2, $brLittle->readUInt16());
    }

    /** @dataProvider binaryReaders */
    public function testInt32($brBig, $brLittle): void
    {
        $this->assertEquals(50331648, $brBig->readInt32());
        $this->assertEquals(3, $brLittle->readInt32());

        $brLittle->setPosition(0);
        $brBig->setPosition(0);

        $this->assertEquals(3, $brBig->readUInt32());
        $this->assertEquals(3, $brLittle->readUInt32());
    }

    /** @dataProvider binaryReaders */
    public function testInt64($brBig, $brLittle): void
    {
        $this->assertEquals(12885059444, $brBig->readInt64());
        $this->assertEquals(8387672839590772739, $brLittle->readInt64());

        $brLittle->setPosition(0);
        $brBig->setPosition(0);

        $this->assertEquals(12885059444, $brBig->readUInt64());
        $this->assertEquals(8387672839590772739, $brLittle->readUInt64());
    }

    /** @dataProvider binaryReaders */
    public function testSingle(BinaryReader $brBig, BinaryReader $brLittle): void
    {
        $brBig->setPosition(16);
        $brLittle->setPosition(16);

        $this->assertEquals(1.0, $brBig->readSingle());
        $this->assertEquals(1.0, $brLittle->readSingle());

        $this->assertEquals(-1.0, $brBig->readSingle());
        $this->assertEquals(-1.0, $brLittle->readSingle());
    }

    /** @dataProvider binaryReaders */
    public function testAlign($brBig, $brLittle): void
    {
        $brBig->readBits(30);
        $brLittle->readBits(30);

        $brBig->align();
        $brLittle->align();

        $this->assertEquals(0, $brBig->getCurrentBit());
        $this->assertEquals(0, $brLittle->getCurrentBit());
        $this->assertFalse($brBig->getNextByte());
        $this->assertFalse($brLittle->getNextByte());
        $this->assertEquals(2, $brBig->readUInt16());
        $this->assertEquals(2, $brLittle->readUInt16());
    }

    /** @dataProvider binaryReaders */
    public function testBytes($brBig, $brLittle): void
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $brBig->readBytes(5));
        $this->assertEquals('test!', $brLittle->readBytes(5));
    }

    /** @dataProvider binaryReaders */
    public function testString($brBig, $brLittle): void
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $brBig->readString(5));
        $this->assertEquals('test!', $brLittle->readString(5));
    }

    /** @dataProvider binaryReaders */
    public function testAlignedString($brBig, $brLittle): void
    {
        $brBig->setPosition(6);
        $brLittle->setPosition(6);

        $brBig->readBits(4);
        $brLittle->readBits(4);

        $this->assertEquals('test!', $brBig->readAlignedString(5));
        $this->assertEquals('test!', $brLittle->readAlignedString(5));
    }

    /** @dataProvider binaryReaders */
    public function testEndianSet($brBig, $brLittle): void
    {
        $brBig->setEndian(Endian::LITTLE);
        $brLittle->setEndian(Endian::BIG);

        $this->assertEquals(Endian::LITTLE, $brBig->getEndian());
        $this->assertEquals(Endian::BIG, $brLittle->getEndian());
    }

    /** @dataProvider binaryReaders */
    public function testExceptionIsThrownIfInvalidEndianSet($brBig, $brLittle): void
    {
        $this->expectException(InvalidDataException::class);

        $brBig->setEndian(12390123);
    }

    /** @dataProvider binaryReaders */
    public function testPositionSet($brBig, $brLittle): void
    {
        $brBig->setPosition(5);
        $this->assertEquals(5, $brBig->getPosition());
    }

    /** @dataProvider binaryReaders */
    public function testEofPosition($brBig, $brLittle): void
    {
        $this->assertEquals(45, $brBig->getEofPosition());
        $this->assertEquals(45, $brLittle->getEofPosition());
    }

    /** @dataProvider binaryReaders */
    public function testNextByte($brBig, $brLittle): void
    {
        $brBig->readBits(70);
        $brLittle->readBits(70);

        $this->assertEquals(101, $brBig->getNextByte());
        $this->assertEquals(101, $brLittle->getNextByte());

        $brBig->setNextByte(5);
        $brLittle->setNextByte(5);

        $this->assertEquals(5, $brBig->getNextByte());
        $this->assertEquals(5, $brLittle->getNextByte());
    }

    /** @dataProvider binaryReaders */
    public function testCurrentBit($brBig, $brLittle): void
    {
        $this->assertEquals(0, $brBig->getCurrentBit());
        $this->assertEquals(0, $brLittle->getCurrentBit());

        $brBig->readBits(3);
        $brLittle->readBits(3);

        $this->assertEquals(3, $brBig->getCurrentBit());
        $this->assertEquals(3, $brLittle->getCurrentBit());

        $brBig->setCurrentBit(7);
        $brLittle->setCurrentBit(7);

        $this->assertEquals(7, $brBig->getCurrentBit());
        $this->assertEquals(7, $brLittle->getCurrentBit());
    }

    /** @dataProvider binaryReaders */
    public function testInputString($brBig, $brLittle): void
    {
        $brBig->setInputString('foo');
        $this->assertEquals('foo', $brBig->getInputString());
    }

    /** @dataProvider binaryReaders */
    public function testInputHandle($brBig, $brLittle): void
    {
        // Create a handle in-memory
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, 'Test');
        rewind($handle);

        $brBig->setInputHandle($handle);
        $this->assertEquals($handle, $brBig->getInputHandle());
    }

    /** @dataProvider binaryReaders */
    public function testMachineByteOrder($brBig, $brLittle): void
    {
        $this->assertEquals(Endian::LITTLE, $brBig->getMachineByteOrder());
        $brBig->setMachineByteOrder(Endian::BIG);
        $this->assertEquals(Endian::BIG, $brBig->getMachineByteOrder());
    }

    /** @dataProvider binaryReaders */
    public function testReadFromHandle($brBig, $brLittle): void
    {
        $this->assertEquals('03000000', bin2hex($brLittle->readFromHandle(4)));
        $this->assertEquals(4, $brLittle->getPosition());

        $this->assertEquals('00000003', bin2hex($brBig->readFromHandle(4)));
        $this->assertEquals(4, $brBig->getPosition());
    }

    public function testReaders(): void
    {
        $dataBig = fopen(__DIR__ . '/asset/testfile-big.bin', 'rb');
        $brBig = new BinaryReader($dataBig, Endian::BIG);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Bit', $brBig->bitReader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Byte', $brBig->byteReader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int8', $brBig->int8Reader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int16', $brBig->int16Reader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int32', $brBig->int32Reader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int64', $brBig->int64Reader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Str', $brBig->stringReader);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Single', $brBig->singleReader);
    }
}
