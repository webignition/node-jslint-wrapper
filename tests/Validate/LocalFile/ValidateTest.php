<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\LocalFile;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslintOutput\Exception as NodeJslintOutputException;

class ValidateTest extends BaseTest
{
    const URL_TO_LINT = 'file:/home/example/script.js';

    private $wrapper;

    public function setUp()
    {
        $this->wrapper = $this->getNewWrapper();
        $this->wrapper->getConfiguration()->setUrlToLint(self::URL_TO_LINT);
        $this->setValidatorRawOutput($this->getFixture(str_replace('test', '', $this->getName()) . 'Output.txt'));
    }

    public function testLocalFileNotFound()
    {
        $this->setExpectedException(
            NodeJslintOutputException::class,
            'Input file "/home/example/script.js" not found',
            1
        );
        $this->wrapper->validate();
    }

    public function testIncorrectNodeJsPath()
    {
        $this->setExpectedException(
            NodeJslintOutputException::class,
            'node-jslint not found at "/home/example/node_modules/jslint/bin/jslint.js"',
            3
        );
        $this->wrapper->validate();
    }

    public function testErrorFree()
    {
        $this->wrapper->validate();
    }
}
