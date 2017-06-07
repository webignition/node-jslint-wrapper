<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class SetGetUrlToLintTest extends BaseTest {
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setUrlToLint('foo'));
    }
    
    
    public function testGetReturnsValueSet() {
        $path = 'foo';
        $configuration = new Configuration();
        $this->assertEquals($path, $configuration->setUrlToLint($path)->getUrlToLint());        
    }
    
    
    public function testLocalPathIsTranslatedToFileUrl() {        
        $path = '/home/example/foo/.js';
        $expectedPath = 'file:' . $path;
        $configuration = new Configuration();
        
        $this->assertEquals($expectedPath, $configuration->setUrlToLint($path)->getUrlToLint());                
    }    
}