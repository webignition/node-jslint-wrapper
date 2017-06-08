<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy\Configuration;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\LocalProxy\Configuration;

class SetGetUrlToLintTest extends BaseTest
{
    const VALID_URL = 'http://example.com';
    const INVALID_URL = 'foo';

    public function testSetReturnsSelf()
    {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setUrlToLint(self::VALID_URL));
    }

    public function testGetReturnsValueSet()
    {
        $configuration = new Configuration();
        $this->assertEquals(self::VALID_URL, $configuration->setUrlToLint(self::VALID_URL)->getUrlToLint());
    }

    public function testInvalidUrlThrowsInvalidArgumentException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Url "'.self::INVALID_URL.'" is not valid', 1);

        $configuration = new Configuration();
        $configuration->setUrlToLint(self::INVALID_URL);
    }
}
