<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\NodeJsLint as NodeJsLintFlag;

class FlagTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';
    const EXPECTED_PATH_TO_LINT = '/home/example/script.js';
    
    public function setUp() {  
        $underTestFlagNames = $this->getKeys();       
        
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);        
        
        foreach ($underTestFlagNames as $flagName) {
            $configuration->setFlag($flagName);
        }
        
        $this->assertEquals(
            '/usr/bin/node /usr/share/node-jslint/node_modules/jslint/bin/jslint.js '.$this->getExpectedFlagString().' ' . self::EXPECTED_PATH_TO_LINT . ' 2>&1',
            $configuration->getExecutableCommand()    
        );        
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
    
    public function testAnon_Bitwise() {}
    public function testBitwise_Anon() {}
    public function testEvil_Eqeq() {}
    public function testWhite_Widget() {}
    public function testNode_Vars() {}
    
    public function testSub_Stupid_Properties() {}
    public function testUndef_PlusPlus_Css() {}
    public function testStupid_Windows_Browser() {}
    
    public function testContinue_On_Anon_Fragment() {}
    public function testBitwise_Rhino_On_Undef_Windows_Fragment() {}   

    
    /**
     * 
     * @return string
     */
    private function getKeys() {
        return explode('_', \ICanBoogie\underscore(str_replace('test', '', strtolower($this->getName()))));
    } 
    
    
    /**
     * 
     * @return string
     */
    private function getExpectedFlagString() {
        $flagStrings = array();
        
        foreach ($this->getKeys() as $flagName) {
            $flagStrings[] = '--' . $flagName;
        }
        
        $flagStrings[] = '--' . NodeJsLintFlag::JSON;
        
        return implode(' ', $flagStrings);
    }    
    
}