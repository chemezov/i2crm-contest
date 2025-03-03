<?php

require 'vendor/autoload.php';

use GuzzleHttp\Psr7\Stream;
use Chemezov\I2crmTest\EncryptingStream;

/**
 * Image
 */
$imageFile = new Stream(fopen('samples/IMAGE.original', 'r'));
$imageMediaKey = file_get_contents('samples/IMAGE.key');
$imageApplicationInfo = 'WhatsApp Image Keys';

$imageEncryptedStream = new EncryptingStream($imageFile, $imageMediaKey, $imageApplicationInfo);
file_put_contents('output/IMAGE.encrypted', $imageEncryptedStream->getContents());

/**
 * Audio
 */
$audioFile = new Stream(fopen('samples/AUDIO.original', 'r'));
$audioMediaKey = file_get_contents('samples/AUDIO.key');
$audioApplicationInfo = 'WhatsApp Audio Keys';

$audioEncryptedStream = new EncryptingStream($audioFile, $audioMediaKey, $audioApplicationInfo);
file_put_contents('output/AUDIO.encrypted', $audioEncryptedStream->getContents());

/**
 * Video
 */
$videoFile = new Stream(fopen('samples/VIDEO.original', 'r'));
$videoMediaKey = file_get_contents('samples/VIDEO.key');
$videoApplicationInfo = 'WhatsApp Video Keys';

$videoEncryptedStream = new EncryptingStream($videoFile, $videoMediaKey, $videoApplicationInfo);
file_put_contents('output/VIDEO.encrypted', $videoEncryptedStream->getContents());
file_put_contents('output/VIDEO.sidecar', $videoEncryptedStream->getSidecar());

echo "Файлы зашифрованы! Выходные файлы в /output/*\n";
