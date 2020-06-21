<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int64 implements TypeInterface
{
    public string $endianBig = 'N';
    public string $endianLittle = 'V';

    public function read(BinaryReader &$br, int $length = null): string
    {
        if (!$br->canReadBytes(8)) {
            throw new \OutOfBoundsException('Cannot read 64-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::BIG ? $this->endianBig : $this->endianLittle;
        $firstSegment = $br->readFromHandle(4);
        $secondSegment = $br->readFromHandle(4);

        $firstHalf = (string) unpack($endian, $firstSegment)[1];
        $secondHalf = (string) unpack($endian, $secondSegment)[1];

        if ($br->getEndian() == Endian::BIG) {
            $value = bcadd($secondHalf, bcmul($firstHalf, "4294967296"));
        } else {
            $value = bcadd($firstHalf, bcmul($secondHalf, "4294967296"));
        }

        if ($br->getCurrentBit() != 0) {
            $value = $this->bitReader($br, $value);
        }

        return (string) $value;
    }

    public function readSigned(BinaryReader &$br): string
    {
        $value = $this->read($br);
        if (bccomp($value, bcpow('2', '63')) >= 0) {
            $value = bcsub($value, bcpow('2', '64'));
        }

        return (string) $value;
    }

    private function bitReader(BinaryReader &$br, string $data): int
    {
        $bitmask = new BitMask();
        $loMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = ($br->getNextByte() & $hiMask) << 56;
        $miBits = ($data & 0xFFFFFFFFFFFFFF00) >> (8 - $br->getCurrentBit());
        $loBits = ($data & $loMask);
        $br->setNextByte($data & 0xFF);

        return $hiBits | $miBits | $loBits;
    }
}
