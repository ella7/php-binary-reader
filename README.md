PhpBinaryReader
===
![PHP](https://github.com/iBotPeaches/php-binary-reader/workflows/PHP/badge.svg)

Why the fork?
---
It started as trying to dive into a bug on why signed 32bit reads were wrong, then I couldn't work on it locally
due it not being supported to develop on latest (at the time), php 7.4. I got obsessed with adapting to new language
features and it just kinda didn't stop until I explored GitHub Actions and more.

Why?
---
You probably wouldn't be here if you hadn't run into a scenario where you needed to leverage PHP to read a stream of
binary data. The honest truth is PHP really stinks at this stuff, but as long as we're going to be using it we may as
well do our best to make it as painless as possible.

The purpose of this binary reader is to accept a string of file contents or file resource and provide a set of methods 
inspired by .NET to traverse it.

Note on Endians
---
The reader is designed to work on little endian machines, which is going to apply to most scenarios as all x86 and x86-64
machines are little endian. If you have somehow found yourself on a big endian machine, you need to inform the class or
you may not be able to properly read signed integers in the file you're parsing.
```php
$fileData = file_get_contents('somefile.bin');
$br = new BinaryReader($fileData);
$br->setMachineByteOrder(Endian::BIG);
...
```

Example Usage
---

```php
$fileData = file_get_contents('somefile.bin');
$br = new BinaryReader($fileData, Endian::LITTLE);
// or
$fileResource = fopen('somefile.bin', 'rb');
$br = new BinaryReader($fileResource, Endian::LITTLE);
$magic = $br->readUInt32();
$offset = $br->readUInt16();
$length = $br->readUInt16();
...
```

Methods
---
**__construct($str, $endian)** a string must be provided to use this class, an endian is optional (use the constants in the Endian class), it will default to little if not provided.

**readUInt8()** returns a single 8 bit byte as an unsigned integer

**readInt8()** returns a single 8 bit byte as a signed integer

**readUInt16()** returns a 16-bit short as an unsigned integer

**readInt16()** returns a 16-bit short as a signed integer

**readUInt32()** returns a 32-bit unsigned integer

**readInt32()** returns a 32-bit signed integer

**readUInt64()** returns a 64-bit unsigned integer

**readInt64()** returns a 64-bit signed integer

**readSingle()** returns a 4-bytes floating-point

**readUBits($length)** returns a variable length of bits (unsigned)

**readBits($length)** returns a variable length of bits (signed)

**readBytes($length)** returns a variable length of bytes

**readString($length)** returns a variable length string

**readAlignedString($length)** aligns the pointer to 0 bits and returns a variable length string

**align()** aligns the pointer back to 0 bits

**isEof()** returns true if the pointer is on the last byte of the file

**getPosition()** returns the current byte position in the file

**setPosition($position)** sets the current byte position

**getCurrentBit()** returns the current bit position in the file

**setCurrentBit($currentBit)** sets the current bit position

Contributing
---
Contributions must follow the PSR2 coding standards and must not degrade 100% coverage.

Acknowledgements
---
Significant portions of the work is based on Graylin Kim's Python bit/byte reader in `sc2reader`_

.. _sc2reader: https://github.com/GraylinKim/sc2reader
