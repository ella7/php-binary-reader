<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int16 implements TypeInterface
{
    public string $endianBig = 'n';
    public string $endianLittle = 'v';

    public function read(BinaryReader &$br, int $length = null): int
    {
        if (!$br->canReadBytes(2)) {
            throw new \OutOfBoundsException('Cannot read 16-bit int, it exceeds the boundary of the file');
        }
        $endian = $br->getEndian() == Endian::BIG ? $this->endianBig : $this->endianLittle;
        $segment = $br->readFromHandle(2);

        $data = unpack($endian, $segment);
        $data = $data[1];

        if ($br->getCurrentBit() != 0) {
            $data = $this->bitReader($br, $data);
        }

        return $data;
    }

    public function readSigned(BinaryReader &$br): int
    {
        $this->endianBig = 's';
        $this->endianLittle = 's';

        $value = $this->read($br);

        $this->endianBig = 'n';
        $this->endianLittle = 'v';

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
}
