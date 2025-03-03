<?php

namespace Chemezov\I2crmTest;

use phpseclib3\Crypt\AES;
use Psr\Http\Message\StreamInterface;

class EncryptingStream extends AbstractStream
{
    public function __construct(StreamInterface $stream, string $mediaKey, string $applicationInfo)
    {
        parent::__construct($stream, $mediaKey, $applicationInfo);

        $this->createStream($this->encrypt());
    }

    public function encrypt(): string
    {
        $aes = new AES('cbc');
        $aes->setKey($this->cipherKey);
        $aes->setIV($this->iv);

        $data = $this->original->getContents();
        $encryptedData = $aes->encrypt($data);

        $hmac = $this->hmac($this->iv . $encryptedData, $this->macKey);
        $mac = substr($hmac, 0, 10);

        return $encryptedData . $mac;
    }

    public function getSidecar(): string
    {
        $this->stream->rewind();
        $sidecar = '';
        $blockSize = 64 * 1024;
        $chunkOffset = 0;

        while ($chunkOffset < $this->stream->getSize()) {
            $this->stream->seek($chunkOffset);

            $chunk = $this->stream->read($blockSize + 16);

            if (strlen($chunk) === 0) {
                break;
            }

            $hmac = $this->hmac($chunk, $this->macKey);
            $mac = substr($hmac, 0, 10);

            $sidecar .= $mac;

            $chunkOffset += $blockSize;
        }

        return $sidecar;
    }
}
