<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;

class Int8 implements TypeInterface
{
    public string $endian = 'C';

    public function read(BinaryReader &$br, int $length = null): int
    {
        if (!$br->canReadBytes(1)) {
            throw new \OutOfBoundsException('Cannot read 8-bit int, it exceeds the boundary of the file');
        }

        $segment = $br->readFromHandle(1);

        $data = unpack($this->endian, $segment);
        $data = $data[1];

        if ($br->getCurrentBit() != 0) {
            $data = $this->bitReader($br, $data);
        }

        return $data;
    }

    public function readSigned(BinaryReader &$br): int
    {
        $this->endian = 'c';
        $value = $this->read($br);
        $this->endian = 'C';

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
}
