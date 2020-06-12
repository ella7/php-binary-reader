<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;

class BitMask
{
    const MASK_LO = 0;
    const MASK_HI = 1;

    private array $bitMasks = [
        [0x00, 0xFF],
        [0x01, 0x7F],
        [0x03, 0x3F],
        [0x07, 0x1F],
        [0x0F, 0x0F],
        [0x1F, 0x07],
        [0x3F, 0x03],
        [0x7F, 0x01],
        [0xFF, 0x00]
    ];

    public function getBitMasks(): array
    {
        return $this->bitMasks;
    }

    public function getMask(int $bit, int $type)
    {
        $bit = (int) $bit >= 0 && (int) $bit <= 8 ? $bit : 0;

        if ($type == self::MASK_LO) {
            return $this->getBitMasks()[$bit][self::MASK_LO];
        } elseif ($type == self::MASK_HI) {
            return $this->getBitMasks()[$bit][self::MASK_HI];
        } else {
            throw new InvalidDataException('You can only request a lo or hi bit mask using this method');
        }
    }
}
