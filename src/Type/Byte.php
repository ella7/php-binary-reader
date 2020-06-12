<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

class Byte implements TypeInterface
{
    public function read(BinaryReader &$br, int $length = null): string
    {
        if (empty($length)) {
            throw new InvalidDataException('The length parameter must be an integer');
        }

        $br->align();

        if (($br->getPosition() + $length) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read bytes, it exceeds the boundary of the file');
        }

        $segment = substr($br->getInputString(), $br->getPosition(), $length);

        $br->setPosition($br->getPosition() + $length);

        return $segment;
    }
}
