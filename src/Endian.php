<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

class Endian
{
    public const BIG = 1;
    public const LITTLE = 0;

    public function convert(int $value)
    {
        $data = dechex($value);

        if (strlen($data) <= 2) {
            return $value;
        }

        $unpack = unpack("H*", strrev(pack("H*", $data)));
        return hexdec($unpack[1]);
    }
}
