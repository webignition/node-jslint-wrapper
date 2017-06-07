<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class SetGetNodePathTest extends BaseTest {
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setNodePath('foo'));
    }
    
    
    public function testGetReturnsValueSet() {
        $path = 'foo';
        $configuration = new Configuration();
        $this->assertEquals($path, $configuration->setNodePath($path)->getNodePath());        
    }
    
    
    public function testGetBeforeSetReturnsDefault() {
        $configuration = new Configuration();
        $this->assertEquals(Configuration::DEFAULT_NODE_PATH, $configuration->getNodePath()); 
    }
    
}