<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Mock\LocalProxy\LocalProxy;

class GetLocalRemoteResourcePathTest extends BaseTest {
    
    public function testLocalRemoteResourcePathChangesWithUrlToLint() {        
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            "HTTP/1.0 200 OK\nContent-Type:application/javascript",
            "HTTP/1.0 200 OK\nContent-Type:application/javascript"
        )));
        
        $localProxy = new LocalProxy();
        $localProxy->getConfiguration()->setBaseRequest($this->getHttpClient()->get());        
        $localProxy->getConfiguration()->setUrlToLint('http://example.com/one.js');
        
        $localPathOne = $localProxy->getLocalRemoteResourcePath();
        
        $localProxy->getConfiguration()->setUrlToLint('http://example.com/two.js');
        $localPathTwo = $localProxy->getLocalRemoteResourcePath();
        
        $this->assertFalse($localPathOne === $localPathTwo);
    }
    
}