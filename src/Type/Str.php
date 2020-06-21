<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

class Str implements TypeInterface
{
    public function read(BinaryReader &$br, int $length = null): string
    {
        if (empty($length)) {
            throw new InvalidDataException('The length parameter must be an integer');
        }

        if (!$br->canReadBytes($length)) {
            throw new \OutOfBoundsException('Cannot read string, it exceeds the boundary of the file');
        }

        return $br->readFromHandle($length);
    }

    public function readAligned(BinaryReader &$br, int $length): string
    {
        $br->align();

        return $this->read($br, $length);
    }
}
