<?php

namespace Chemezov\I2crmTest;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

abstract class AbstractStream implements StreamInterface
{
    protected StreamInterface $original; // Поток с оригинальным файлов
    protected StreamInterface $stream; // Поток с расшифрованным/зашифрованным файлом
    protected string $cipherKey;
    protected string $iv;
    protected string $macKey;
    protected string $refKey;

    public function __construct(StreamInterface $stream, string $mediaKey, string $applicationInfo)
    {
        $this->original = $stream;
        $expandedKey = $this->hkdf($mediaKey, 112, $applicationInfo);
        $this->iv = substr($expandedKey, 0, 16);
        $this->cipherKey = substr($expandedKey, 16, 32);
        $this->macKey = substr($expandedKey, 48, 32);
        $this->refKey = substr($expandedKey, 80);
    }

    protected function hkdf(string $key, int $length, string $info): string
    {
        return hash_hkdf('sha256', $key, $length, $info, '');
    }

    protected function hmac(string $data, string $key): string
    {
        return hash_hmac('sha256', $data, $key, true);
    }

    protected function createStream(string $data): void
    {
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $data);
        rewind($resource);

        $this->stream = new Stream($resource);
    }

    public function getContents(): string
    {
        return $this->stream->getContents();
    }

    public function __toString(): string
    {
        return $this->stream->getContents();
    }

    public function close(): void
    {
        $this->stream->close();
    }

    public function detach()
    {
        return $this->stream->detach();
    }

    public function getSize(): ?int
    {
        return $this->stream->getSize();
    }

    public function tell(): int
    {
        return $this->stream->tell();
    }

    public function eof(): bool
    {
        return $this->stream->eof();
    }

    public function isSeekable(): bool
    {
        return $this->stream->isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        $this->stream->seek($offset, $whence);
    }

    public function rewind(): void
    {
        $this->stream->rewind();
    }

    public function isWritable(): bool
    {
        return $this->stream->isWritable();
    }

    public function write($string): int
    {
        return $this->stream->write($string);
    }

    public function isReadable(): bool
    {
        return $this->stream->isReadable();
    }

    public function read($length): string
    {
        return $this->stream->read($length);
    }

    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }
}
