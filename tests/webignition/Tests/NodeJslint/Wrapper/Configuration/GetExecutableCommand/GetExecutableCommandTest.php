<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class GetExecutableCommandTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';
    const EXPECTED_PATH_TO_LINT = '/home/example/script.js';
    
    public function testWithoutUrlToLintThrowsUnexpectedValueException() {
        $this->setExpectedException('UnexpectedValueException', 'URL to lint not present; set this first with ->setUrlToLint()', 1);
        
        $configuration = new Configuration();
        $configuration->getExecutableCommand();
    }

    
}