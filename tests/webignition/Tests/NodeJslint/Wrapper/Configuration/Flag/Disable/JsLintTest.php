<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\Flag\Disable;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class JsLintTest extends BaseTest {
   
    public function setUp() {
        $key = str_replace('test', '', strtolower($this->getName()));
        $flagNames = $this->getAllFlagNames();
        
        $flagName = $flagNames[$key];
        
        $configuration = new Configuration();
        $configuration->disableFlag($flagName);
    }

    public function testAnon() {}
    public function testBitwise() {}
    public function testBrowser() {}
    public function testCap() {}
    public function testContinue() {}
    public function testCss() {}
    public function testDebug() {}
    public function testDevel() {}
    public function testEqeq() {}
    public function testEs5() {}
    public function testEvil() {}
    public function testForin() {}
    public function testFragment() {}
    public function testNewcap() {}
    public function testNode() {}
    public function testNomen() {}
    public function testOn() {}
    public function testPassfail() {}
    public function testPlusplus() {}
    public function testProperties() {}
    public function testRegexp() {}
    public function testRhino() {}
    public function testUndef() {}
    public function testUnparam() {}
    public function testSloppy() {}
    public function testStupid() {}
    public function testSub() {}
    public function testVars() {}
    public function testWhite() {}
    public function testWidget() {}
    public function testWindows() {}
    
    
}