<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\LocalFile;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class ValidateTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';
    
    private $wrapper;
    
    public function setUp() {
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $this->wrapper = $this->getNewWrapper();
        $this->wrapper->setValidatorRawOutput($this->getFixture(str_replace('test', '', $this->getName()) . 'Output.txt'));
        $this->wrapper->enableDeferToParentIfNoRawOutput();        
        $this->wrapper->setConfiguration($configuration);     
    }

    public function testLocalFileNotFound() {
        $this->setExpectedException('webignition\NodeJslintOutput\Exception', 'Input file "/home/example/script.js" not found', 1);
        $this->wrapper->validate();
    }
    
    
    public function testErrorFree() {
        $this->wrapper->validate();
    }
    
}