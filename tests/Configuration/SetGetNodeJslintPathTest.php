<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class SetGetNodeJslintPathTest extends BaseTest {
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setNodeJslintPath('foo'));
    }
    
    
    public function testGetReturnsValueSet() {
        $path = '/foo/jslint.js';
        $configuration = new Configuration();
        $this->assertEquals($path, $configuration->setNodeJslintPath($path)->getNodeJslintPath());        
    }
    
    
    public function testGetBeforeSetReturnsDefault() {
        $configuration = new Configuration();
        $this->assertEquals(Configuration::DEFAULT_NODE_JSLINT_PATH, $configuration->getNodeJslintPath()); 
    }    
    
}