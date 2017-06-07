<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy;

use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy;
use webignition\Tests\NodeJslint\Wrapper\BaseTest;

class GetLocalRemoteResourcePathTest extends BaseTest {

    public function testLocalRemoteResourcePathChangesWithUrlToLint() {
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            "HTTP/1.0 200 OK\nContent-Type:application/javascript",
            "HTTP/1.0 200 OK\nContent-Type:application/javascript"
        )));

        $localProxy = new LocalProxy();
        $localProxy->getConfiguration()->setHttpClient($this->getHttpClient());
        $localProxy->getConfiguration()->setUrlToLint('http://example.com/one.js');

        $localPathOne = $localProxy->getLocalRemoteResourcePath();

        $localProxy->getConfiguration()->setUrlToLint('http://example.com/two.js');
        $localPathTwo = $localProxy->getLocalRemoteResourcePath();

        $this->assertFalse($localPathOne === $localPathTwo);
    }

}