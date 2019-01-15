<?php

namespace yii\authclient\tests\signature;

use yii\authclient\signature\HmacSha1;
use yii\authclient\tests\TestCase;

class HmacSha1Test extends TestCase
{
    public function testGenerateSignature()
    {
        $signatureMethod = new HmacSha1();

        $baseString = 'test_base_string';
        $key = 'test_key';

        $signature = $signatureMethod->generateSignature($baseString, $key);
        $this->assertNotEmpty($signature, 'Unable to generate signature!');
    }
}
