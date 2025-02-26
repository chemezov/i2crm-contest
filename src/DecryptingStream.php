<?php

namespace Chemezov\I2crmTest;

use phpseclib3\Crypt\AES;
use Psr\Http\Message\StreamInterface;

class DecryptingStream extends AbstractStream
{
    public function __construct(StreamInterface $stream, string $mediaKey, string $applicationInfo)
    {
        parent::__construct($stream, $mediaKey, $applicationInfo);

        $this->createStream($this->decrypt());
    }

    public function decrypt(): string
    {
        $data = $this->original->getContents();
        $file = substr($data, 0, -10);
        $mac = substr($data, -10);

        $hmac = $this->hmac($this->iv . $file, $this->macKey);
        $expectedMac = substr($hmac, 0, 10);

        if (!hash_equals($expectedMac, $mac)) {
            throw new \Exception("MAC не совпадает, данные повреждены");
        }

        $aes = new AES('cbc');
        $aes->setKey($this->cipherKey);
        $aes->setIV($this->iv);

        return $aes->decrypt($file);
    }
}
