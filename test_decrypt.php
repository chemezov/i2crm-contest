<?php

require 'vendor/autoload.php';

use GuzzleHttp\Psr7\Stream;
use Chemezov\I2crmTest\DecryptingStream;

/**
 * Image
 */
$imageFile = new Stream(fopen('samples/IMAGE.encrypted', 'r'));
$imageMediaKey = file_get_contents('samples/IMAGE.key');
$imageApplicationInfo = 'WhatsApp Image Keys';

$imageEncryptedStream = new DecryptingStream($imageFile, $imageMediaKey, $imageApplicationInfo);
file_put_contents('output/IMAGE.original', $imageEncryptedStream->getContents());

/**
 * Audio
 */
$audioFile = new Stream(fopen('samples/AUDIO.encrypted', 'r'));
$audioMediaKey = file_get_contents('samples/AUDIO.key');
$audioApplicationInfo = 'WhatsApp Audio Keys';

$audioEncryptedStream = new DecryptingStream($audioFile, $audioMediaKey, $audioApplicationInfo);
file_put_contents('output/AUDIO.original', $audioEncryptedStream->getContents());

/**
 * Video
 */
$videoFile = new Stream(fopen('samples/VIDEO.encrypted', 'r'));
$videoMediaKey = file_get_contents('samples/VIDEO.key');
$videoApplicationInfo = 'WhatsApp Video Keys';

$videoEncryptedStream = new DecryptingStream($videoFile, $videoMediaKey, $videoApplicationInfo);
file_put_contents('output/VIDEO.original', $videoEncryptedStream->getContents());

echo "Файлы расшифрованы! Выходные файлы в /output/*\n";
