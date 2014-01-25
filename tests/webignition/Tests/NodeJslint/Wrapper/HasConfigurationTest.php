<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Wrapper as NodeJsLintWrapper;

class HasConfigurationTest extends \PHPUnit_Framework_TestCase {
    
    public function testHasNotConfigurationByDefault() {
        $wrapper = new NodeJsLintWrapper();
        $this->assertFalse($wrapper->hasConfiguration());
    }
    
    public function testHasConfigurationWhenConfigurationIsSet() {
        $wrapper = new NodeJsLintWrapper();
        $this->assertTrue($wrapper->setConfiguration(new Configuration())->hasConfiguration());
    }    
    
}