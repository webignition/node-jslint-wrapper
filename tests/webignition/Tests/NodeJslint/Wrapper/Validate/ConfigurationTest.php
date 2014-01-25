<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;

class ConfigurationTest extends BaseTest {    
    
    public function testValidateWithoutSettingConfigurationThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $this->getNewWrapper()->validate();
    }
    
}