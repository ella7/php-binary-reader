<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;

class Single implements TypeInterface
{
    public function read(BinaryReader &$br, int $length = null): float
    {
        if (!$br->canReadBytes(4)) {
            throw new \OutOfBoundsException('Cannot read 4-bytes floating-point, it exceeds the boundary of the file');
        }

        $segment = $br->readFromHandle(4);

        if ($br->getCurrentBit() !== 0) {
            $data = unpack('N', $segment)[1];
            $data = $this->bitReader($br, $data);

            $endian = $br->getMachineByteOrder() === $br->getEndian() ? 'N' : 'V';
            $segment = pack($endian, $data);
        } elseif ($br->getMachineByteOrder() !== $br->getEndian()) {
            $segment = pack('N', unpack('V', $segment)[1]);
        }

        return unpack('f', $segment)[1];
    }

    private function bitReader(BinaryReader $br, int $data): int
    {
        $mask = 0x7FFFFFFF >> ($br->getCurrentBit() - 1);
        $value = (($data >> (8 - $br->getCurrentBit())) & $mask) | ($br->getNextByte() << (24 + $br->getCurrentBit()));
        $br->setNextByte($data & 0xFF);

        return $value;
    }
}
