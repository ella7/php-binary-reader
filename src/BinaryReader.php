<?php

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;
use PhpBinaryReader\Type\Bit;
use PhpBinaryReader\Type\Byte;
use PhpBinaryReader\Type\Int8;
use PhpBinaryReader\Type\Int16;
use PhpBinaryReader\Type\Int32;
use PhpBinaryReader\Type\Str;

class BinaryReader
{
    /**
     * @var int
     */
    private $machineByteOrder = Endian::ENDIAN_LITTLE;

    /**
     * @var Str
     */
    private $inputString;

    /**
     * @var int
     */
    private $currentBit;

    /**
     * @var mixed
     */
    private $nextByte;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $eofPosition;

    /**
     * @var Str
     */
    private $endian;

    /**
     * @var \PhpBinaryReader\Type\Byte
     */
    private $byteReader;

    /**
     * @var \PhpBinaryReader\Type\Bit
     */
    private $bitReader;

    /**
     * @var \PhpBinaryReader\Type\Str
     */
    private $stringReader;

    /**
     * @var \PhpBinaryReader\Type\Int8
     */
    private $int8Reader;

    /**
     * @var \PhpBinaryReader\Type\Int16
     */
    private $int16Reader;

    /**
     * @var \PhpBinaryReader\Type\Int32
     */
    private $int32Reader;

    /**
     * @param  Str                    $str
     * @param  int|Str                $endian
     * @throws \InvalidArgumentException
     */
    public function __construct($str, $endian = Endian::ENDIAN_LITTLE)
    {
        $this->eofPosition = strlen($str);

        $this->setEndian($endian);
        $this->setInputString($str);
        $this->setNextByte(false);
        $this->setCurrentBit(0);
        $this->setPosition(0);
    }

    /**
     * @return bool
     */
    public function isEof()
    {
        if ($this->getPosition() >= $this->getEofPosition()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public function align()
    {
        $this->setCurrentBit(0);
        $this->setNextByte(false);
    }

    /**
     * @param  int $count
     * @return int
     */
    public function readBits($count)
    {
        return $this->getBitReader()->readSigned($this, $count);
    }

    /**
     * @param  int $count
     * @return int
     */
    public function readUBits($count)
    {
        return $this->getBitReader()->read($this, $count);
    }

    /**
     * @param  int $count
     * @return int
     */
    public function readBytes($count)
    {
        return $this->getByteReader()->read($this, $count);
    }

    /**
     * @return int
     */
    public function readInt8()
    {
        return $this->getInt8Reader()->readSigned($this);
    }

    /**
     * @return int
     */
    public function readUInt8()
    {
        return $this->getInt8Reader()->read($this);
    }

    /**
     * @return int
     */
    public function readInt16()
    {
        return $this->getInt16Reader()->readSigned($this);
    }

    /**
     * @return string
     */
    public function readUInt16()
    {
        return $this->getInt16Reader()->read($this);
    }

    /**
     * @return int
     */
    public function readInt32()
    {
        return $this->getInt32Reader()->readSigned($this);
    }

    /**
     * @return int
     */
    public function readUInt32()
    {
        return $this->getInt32Reader()->read($this);
    }

    /**
     * @param  int    $length
     * @return Str
     */
    public function readString($length)
    {
        return $this->getStringReader()->read($this, $length);
    }

    /**
     * @param  int    $length
     * @return Str
     */
    public function readAlignedString($length)
    {
        return $this->getStringReader()->readAligned($this, $length);
    }

    /**
     * @param  int   $machineByteOrder
     * @return $this
     */
    public function setMachineByteOrder($machineByteOrder)
    {
        $this->machineByteOrder = $machineByteOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getMachineByteOrder()
    {
        return $this->machineByteOrder;
    }

    /**
     * @param  Str $inputString
     * @return $this
     */
    public function setInputString($inputString)
    {
        $this->inputString = $inputString;

        return $this;
    }

    /**
     * @return Str
     */
    public function getInputString()
    {
        return $this->inputString;
    }

    /**
     * @param  mixed $nextByte
     * @return $this
     */
    public function setNextByte($nextByte)
    {
        $this->nextByte = $nextByte;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNextByte()
    {
        return $this->nextByte;
    }

    /**
     * @param  int   $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getEofPosition()
    {
        return $this->eofPosition;
    }

    /**
     * @param  Str               $endian
     * @return $this
     * @throws InvalidDataException
     */
    public function setEndian($endian)
    {
        if ($endian == 'big' || $endian == Endian::ENDIAN_BIG) {
            $this->endian = Endian::ENDIAN_BIG;
        } elseif ($endian == 'little' || $endian == Endian::ENDIAN_LITTLE) {
            $this->endian = Endian::ENDIAN_LITTLE;
        } else {
            throw new InvalidDataException('Endian must be set as big or little');
        }

        return $this;
    }

    /**
     * @return Str
     */
    public function getEndian()
    {
        return $this->endian;
    }

    /**
     * @param  int   $currentBit
     * @return $this
     */
    public function setCurrentBit($currentBit)
    {
        $this->currentBit = $currentBit;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentBit()
    {
        return $this->currentBit;
    }

    /**
     * @return \PhpBinaryReader\Type\Bit
     */
    public function getBitReader()
    {
        if (!$this->bitReader instanceof Bit) {
            $this->bitReader = new Bit();
        }

        return $this->bitReader;
    }

    /**
     * @return \PhpBinaryReader\Type\Byte
     */
    public function getByteReader()
    {
        if (!$this->byteReader instanceof Byte) {
            $this->byteReader = new Byte();
        }

        return $this->byteReader;
    }

    /**
     * @return \PhpBinaryReader\Type\Int8
     */
    public function getInt8Reader()
    {
        if (!$this->int8Reader instanceof Int8) {
            $this->int8Reader = new Int8();
        }

        return $this->int8Reader;
    }

    /**
     * @return \PhpBinaryReader\Type\Int16
     */
    public function getInt16Reader()
    {
        if (!$this->int16Reader instanceof Int16) {
            $this->int16Reader = new Int16();
        }

        return $this->int16Reader;
    }

    /**
     * @return \PhpBinaryReader\Type\Int32
     */
    public function getInt32Reader()
    {
        if (!$this->int32Reader instanceof Int32) {
            $this->int32Reader = new Int32();
        }

        return $this->int32Reader;
    }

    /**
     * @return \PhpBinaryReader\Type\Str
     */
    public function getStringReader()
    {
        if (!$this->stringReader instanceof Str) {
            $this->stringReader = new Str();
        }

        return $this->stringReader;
    }
}
