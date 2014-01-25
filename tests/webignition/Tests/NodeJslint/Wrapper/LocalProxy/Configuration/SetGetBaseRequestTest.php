<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy\Configuration;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\LocalProxy\Configuration;

class SetGetBaseRequestTest extends BaseTest {
    
    public function testGetDefaultBaseRequest() {        
        $configuration = new Configuration();
        $this->assertEquals($this->getHttpClient()->get(), $configuration->getBaseRequest());
    }
    
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setBaseRequest($this->getHttpClient()->get()));
    }
    
    public function testSetGetBaseRequest() {        
        $baseRequest = $this->getHttpClient()->get();
        $baseRequest->setAuth('example_user', 'example_password');
        
        $configuration = new Configuration();
        $configuration->setBaseRequest($baseRequest);
        
        $this->assertEquals('example_user', $configuration->getBaseRequest()->getUsername());
        $this->assertEquals($baseRequest->getUsername(), $configuration->getBaseRequest()->getUsername());
    }
    
}