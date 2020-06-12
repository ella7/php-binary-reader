<?php
declare(strict_types = 1);

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;

interface TypeInterface
{
    public function read(BinaryReader &$br, ?int $length);
}
