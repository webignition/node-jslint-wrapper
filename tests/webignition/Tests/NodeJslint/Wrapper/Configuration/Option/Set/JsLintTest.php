<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\Flag\Option;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint;

class JsLintTest extends BaseTest {
    
    private $options = array(
        'indent' => JsLint::INDENT,
        'maxerr' => JsLint::MAXERR,
        'maxlen' => JsLint::MAXLEN,
        'predef' => JsLint::PREDEF,
    );
    
    private $values = array(
        JsLint::INDENT => 4,
        JsLint::MAXERR => 50,
        JsLint::MAXLEN => 128,
        JsLint::PREDEF => '$', 
    );
   
    public function setUp() {
        $key = str_replace('test', '', strtolower($this->getName()));        
        
        $optionName = $this->options[$key];
        $optionValue = $this->values[$optionName];
        
        $configuration = new Configuration();
        $configuration->setOption($optionName, $optionValue);
    }

    public function testIndent() {}
    public function testMaxErr() {}
    public function testMaxLen() {}
    public function testPredef() {}
    
    
}