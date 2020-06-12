<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int32 implements TypeInterface
{
    private string $endianBig = 'N';
    private string $endianLittle = 'V';

    public function read(BinaryReader &$br, int $length = null): int
    {
        if (($br->getPosition() + 4) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 32-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::BIG ? $this->getEndianBig() : $this->getEndianLittle();
        $segment = substr($br->getInputString(), $br->getPosition(), 4);

        $data = unpack($endian, $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 4);

        if ($br->getCurrentBit() != 0) {
            $data = $this->bitReader($br, $data);
        }

        return $data;
    }

    public function readSigned(BinaryReader &$br): int
    {
        $this->setEndianBig('l');
        $this->setEndianLittle('l');

        $value = $this->read($br);

        $this->setEndianBig('N');
        $this->setEndianLittle('V');

        if ($br->getMachineByteOrder() != Endian::LITTLE && $br->getEndian() == Endian::LITTLE) {
            $endian = new Endian();

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
        $hiBits = ($br->getNextByte() & $hiMask) << 24;
        $miBits = ($data & 0xFFFFFF00) >> (8 - $br->getCurrentBit());
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
