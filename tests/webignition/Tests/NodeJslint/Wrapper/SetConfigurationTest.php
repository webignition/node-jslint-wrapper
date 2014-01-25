<?php

namespace webignition\Tests\CssValidatorWrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Wrapper as NodeJsLintWrapper;

class SetConfigurationTest extends \PHPUnit_Framework_TestCase {
    
    public function testSetConfigurationReturnsSelf() {
        $wrapper = new NodeJsLintWrapper();
        $this->assertEquals($wrapper, $wrapper->setConfiguration(new Configuration()));        
    }
    
}