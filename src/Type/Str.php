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

        if (($length + $br->getPosition()) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read string, it exceeds the boundary of the file');
        }

        $str = substr($br->getInputString(), $br->getPosition(), $length);
        $br->setPosition($br->getPosition() + $length);

        return $str;
    }

    public function readAligned(BinaryReader &$br, int $length): string
    {
        $br->align();

        return $this->read($br, $length);
    }
}
