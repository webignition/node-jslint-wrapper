<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\NodeJsLint as NodeJsLintFlag;

class FlagTest extends GetExecutableCommandBaseTest {
    
    public function setUp() {  
        $flagsAndValues = $this->getFlagsAndValues();
        
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);                
        
        foreach ($flagsAndValues as $name => $value) {
            if ($value) {
                $configuration->enableFlag($name);
            } else {
                $configuration->disableFlag($name);
            }
        }        
        
        $this->assertEquals(
            $this->getExpectedExecutableCommandPrefix() . ' ' . $this->getExpectedFlagsString().' ' . $this->getExpectedExecutableCommandSuffix(),
            $configuration->getExecutableCommand()    
        );   
        
        
    } 
    
    
    public function testAnon_True() {}
    public function testBitwise_True() {}
    public function testBrowser_True() {}
    public function testCap_True() {}
    public function testContinue_True() {}
    public function testCss_True() {}
    public function testDebug_True() {}
    public function testDevel_True() {}
    public function testEqeq_True() {}
    public function testEs5_True() {}
    public function testEvil_True() {}
    public function testForin_True() {}
    public function testFragment_True() {}
    public function testNewcap_True() {}
    public function testNode_True() {}
    public function testNomen_True() {}
    public function testOn_True() {}
    public function testPassfail_True() {}
    public function testPlusplus_True() {}
    public function testProperties_True() {}
    public function testRegexp_True() {}
    public function testRhino_True() {}
    public function testUndef_True() {}
    public function testUnparam_True() {}
    public function testSloppy_True() {}
    public function testStupid_True() {}
    public function testSub_True() {}
    public function testVars_True() {}
    public function testWhite_True() {}
    public function testWidget_True() {}
    public function testWindows_True() {}  
    public function testAnon_False() {}
    public function testBitwise_False() {}
    public function testBrowser_False() {}
    public function testCap_False() {}
    public function testContinue_False() {}
    public function testCss_False() {}
    public function testDebug_False() {}
    public function testDevel_False() {}
    public function testEqeq_False() {}
    public function testEs5_False() {}
    public function testEvil_False() {}
    public function testForin_False() {}
    public function testFragment_False() {}
    public function testNewcap_False() {}
    public function testNode_False() {}
    public function testNomen_False() {}
    public function testOn_False() {}
    public function testPassfail_False() {}
    public function testPlusplus_False() {}
    public function testProperties_False() {}
    public function testRegexp_False() {}
    public function testRhino_False() {}
    public function testUndef_False() {}
    public function testUnparam_False() {}
    public function testSloppy_False() {}
    public function testStupid_False() {}
    public function testSub_False() {}
    public function testVars_False() {}
    public function testWhite_False() {}
    public function testWidget_False() {}
    public function testWindows_False() {}      
    
    public function testAnon_True_Bitwise_False() {}
    public function testBitwise_True_Anon_False() {}
    public function testEvil_True_Eqeq_True() {}
    public function testWhite_False_Widget_False() {}
    public function testNode_False_VarsTrue() {}
    
    public function testSub_True_Stupid_False_Properties_True() {}
    public function testUndef_False_PlusPlus_True_Css_False() {}
    public function testStupid_True_Windows_True_Browser_False() {}
    
    public function testContinue_False_On_True_Anon_False_Fragment_True() {}
    public function testBitwise_True_Rhino_True_On_False_Undef_False_Windows_False_Fragment_True() {}   

    
    /**
     * 
     * @return string
     */
    private function getExpectedFlagsString() {
        $flagsString = '';
        
        foreach ($this->getFlagsAndValues() as $name => $value) {
            if ($value === true) {
                $value = 'true';
            }

            if ($value === false) {
                $value = 'false';
            }                    

            $flagsString .= '--' . $name . '=' . $value . ' ';
        }
        
        return trim($flagsString) . ' --' . NodeJsLintFlag::JSON . '=true';
    }
    
    
    /**
     * 
     * @return string
     */
    private function getFlagsAndValues() {
        $flagsAndValues = array();        
        
        $rawValues = explode('_', \ICanBoogie\underscore(str_replace('test', '', strtolower($this->getName()))));       
        
        $currentKey = null;
        foreach ($rawValues as $index => $value) {
            if ($index % 2 == 0) {
                $currentKey = $value;
            } else {
                if ($value === 'true') {
                    $value = true;
                }

                if ($value === 'false') {
                    $value = false;
                }                
                
                if (isset($flagsAndValues[$currentKey])) {
                    if (!is_array($flagsAndValues[$currentKey])) {
                        $flagsAndValues[$currentKey] = array($flagsAndValues[$currentKey]);
                    }
                    
                    $flagsAndValues[$currentKey][] = $value;                
                } else {                    
                    $flagsAndValues[$currentKey] = $value;
                }                
            }
        }        
        
        return $flagsAndValues;
    }  
    
}