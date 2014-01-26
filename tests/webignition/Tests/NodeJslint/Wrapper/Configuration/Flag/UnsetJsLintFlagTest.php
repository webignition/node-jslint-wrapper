<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\Flag;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint;

class JsLintFlagTest extends BaseTest {
    
    private $flags = array(
        'anon' => JsLint::ANON,
        'bitwise' => JsLint::BITWISE,
        'browser' => JsLint::BROWSER,
        'cap' => JsLint::CAP,
        'continue' => JsLint::FLAG_CONTINUE,
        'css' => JsLint::CSS,
        'debug' => JsLint::DEBUG,
        'devel' => JsLint::DEVEL,
        'eqeq' => JsLint::EQEQ,
        'es5' => JsLint::ES5,
        'evil' => JsLint::EVIL,
        'forin' => JsLint::FORIN,
        'fragment' => JsLint::FRAGMENT,
        'newcap' => JsLint::NEWCAP,
        'node' => JsLint::NODE,
        'nomen' => JsLint::NOMEN,
        'on' => JsLint::ON,
        'passfail' => JsLint::PASSFAIL,
        'plusplus' => JsLint::PLUSPLUS,
        'properties' => JsLint::PROPERTIES,
        'regexp' => JsLint::REGEXP,
        'rhino' => JsLint::RHINO,
        'undef' => JsLint::UNDEF,
        'unparam' => JsLint::UNPARAM,
        'sloppy' => JsLint::SLOPPY,
        'stupid' => JsLint::STUPID,
        'sub' => JsLint::SUB,
        'vars' => JsLint::VARS,
        'white' => JsLint::WHITE,
        'widget' => JsLint::WIDGET,
        'windows' => JsLint::WINDOWS
    );
   
    public function setUp() {
        $key = str_replace('test', '', strtolower($this->getName()));
        $flagName = $this->flags[$key];
        
        $configuration = new Configuration();
        
        $configuration->enableFlag($flagName);
        $configuration->unsetFlag($flagName);              
        $this->assertFalse($configuration->hasFlag($flagName));        
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