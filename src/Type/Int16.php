<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int16 implements TypeInterface
{
    private string $endianBig = 'n';
    private string $endianLittle = 'v';

    public function read(BinaryReader &$br, int $length = null): int
    {
        if (($br->getPosition() + 2) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 16-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::BIG ? $this->getEndianBig() : $this->getEndianLittle();
        $segment = substr($br->getInputString(), $br->getPosition(), 2);

        $data = unpack($endian, $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 2);

        if ($br->getCurrentBit() != 0) {
            $data = $this->bitReader($br, $data);
        }

        return $data;
    }

    public function readSigned(BinaryReader &$br): int
    {
        $this->setEndianBig('s');
        $this->setEndianLittle('s');

        $value = $this->read($br);

        $this->setEndianBig('n');
        $this->setEndianLittle('v');

        $endian = new Endian();
        if ($br->getMachineByteOrder() != Endian::LITTLE && $br->getEndian() == Endian::LITTLE) {
            return $endian->convert($value);
        } else {
            return $value;
        }
    }

    private function bitReader(BinaryReader &$br, int $data): int
    {
        $bitmask = new BitMask();
        $loMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = ($br->getNextByte() & $hiMask) << 8;
        $miBits = ($data & 0xFF00) >> (8 - $br->getCurrentBit());
        $loBits = ($data & $loMask);
        $br->setNextByte($data & 0xFF);

        return $hiBits | $miBits | $loBits;
    }

    public function setEndianBig(string $endianBig): void
    {
        $this->endianBig = $endianBig;
    }

    public function getEndianBig(): string
    {
        return $this->endianBig;
    }

    public function setEndianLittle(string $endianLittle): void
    {
        $this->endianLittle = $endianLittle;
    }

    public function getEndianLittle(): string
    {
        return $this->endianLittle;
    }
}
