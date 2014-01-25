<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class ValidateTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';

    public function testValidateNonExistentLocalFileUrl() {
        $this->setExpectedException('webignition\NodeJslintOutput\Exception', 'Input file "/home/example/script.js" not found', 1);
        
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $wrapper = $this->getNewWrapper();
        $wrapper->setValidatorRawOutput($this->getFixture('LocalFileNotFoundOutput.txt'));
        $wrapper->enableDeferToParentIfNoRawOutput();        
        $wrapper->setConfiguration($configuration);
        
        $wrapper->validate();
    }
    
    
    public function testValidateLocalFileUrl() {        
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $wrapper = $this->getNewWrapper();
        $wrapper->setValidatorRawOutput($this->getFixture('ErrorFreeOutput.txt'));
        $wrapper->enableDeferToParentIfNoRawOutput();        
        $wrapper->setConfiguration($configuration);
        
        $wrapper->validate();
    }    
    
}