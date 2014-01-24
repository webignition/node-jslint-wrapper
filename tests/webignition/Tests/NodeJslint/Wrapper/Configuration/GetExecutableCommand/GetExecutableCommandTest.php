<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class GetExecutableCommandTest extends BaseTest {
    
    public function testWithoutUrlToLintThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'URL to lint not present; set this first with ->setUrlToLint()', 1);
        
        $configuration = new Configuration();
        $configuration->getExecutableCommand();
    }

    
}