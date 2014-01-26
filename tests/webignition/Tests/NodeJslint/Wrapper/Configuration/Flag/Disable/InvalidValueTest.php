<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\Flag\Disable;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;


class InvalidValueTest extends BaseTest {

    public function testDisableInvalidFlagThrowsInvalidArgumentException() {        
        $flagName = 'foo';
        
        $this->setExpectedException('InvalidArgumentException', 'Flag "'.$flagName.'" is not valid', 1);
        
        $configuration = new Configuration();
        $configuration->disableFlag($flagName);        
    }

    
    
}