<?php
declare(strict_types = 1);

namespace PhpBinaryReader;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    public function binaryReaders(): array
    {
        [[$largeFile, $largeStr]] = $this->largeReaders();
        [[$littleFile, $littleStr]] = $this->littleReaders();

        return [
            [$largeFile, $littleFile],
            [$largeStr, $littleStr],
        ];
    }

    public function littleReaders(): array
    {
        $fileLittle = __DIR__ . '/asset/testfile-little.bin';
        $brLittleFile = new BinaryReader(fopen($fileLittle, 'rb'), Endian::LITTLE);
        $brLittleStr = new BinaryReader(file_get_contents($fileLittle), Endian::LITTLE);

        return [[$brLittleFile, $brLittleStr]];
    }

    public function largeReaders(): array
    {
        $fileBig = __DIR__ . '/asset/testfile-big.bin';
        $brBigFile = new BinaryReader(fopen($fileBig, 'rb'), Endian::BIG);
        $brBigStr = new BinaryReader(file_get_contents($fileBig), Endian::BIG);

        return [[$brBigFile, $brBigStr]];
    }
}
