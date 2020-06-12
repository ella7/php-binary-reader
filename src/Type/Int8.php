<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;

class Int8 implements TypeInterface
{
    private string $endian = 'C';

    public function read(BinaryReader &$br, int $length = null): int
    {
        if (($br->getPosition() + 1) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 32-bit int, it exceeds the boundary of the file');
        }

        $segment = substr($br->getInputString(), $br->getPosition(), 1);

        $data = unpack($this->getEndian(), $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 1);

        if ($br->getCurrentBit() != 0) {
            $data = $this->bitReader($br, $data);
        }

        return $data;
    }

    public function readSigned(BinaryReader &$br): int
    {
        $this->setEndian('c');
        $value = $this->read($br);
        $this->setEndian('C');

        return $value;
    }

    private function bitReader(BinaryReader &$br, int $data): int
    {
        $bitmask = new BitMask();
        $loMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = $br->getNextByte() & $hiMask;
        $loBits = $data & $loMask;
        $br->setNextByte($data);

        return $hiBits | $loBits;
    }

    public function setEndian(string $endian): void
    {
        $this->endian = $endian;
    }

    public function getEndian(): string
    {
        return $this->endian;
    }
}
