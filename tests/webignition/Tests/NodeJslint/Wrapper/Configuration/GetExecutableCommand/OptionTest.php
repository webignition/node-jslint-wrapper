<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class OptionTest extends GetExecutableCommandBaseTest {
    
    public function setUp() {
        $optionsAndValues = $this->getOptionsAndValues();
        
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);        
        
        foreach ($optionsAndValues as $optionName => $optionValue) {
            $configuration->setOption($optionName, $optionValue);
        }
        
        $this->assertEquals(
            $this->getExpectedFlaglessExecutableCommandPrefix() . ' ' . $this->getExpectedOptionsString() . ' ' . $this->getExpectedExecutableCommandSuffix(),
            $configuration->getExecutableCommand()
        );        
    }
    
    public function testIndent_1() {}
    public function testIndent_2() {}
    public function testIndent_3() {}
    public function testMaxErr_50() {}
    public function testMaxErr_100() {}
    public function testMaxLen_128() {}
    public function testMaxLen_512() {}
    public function testPredef_Foo() {}
    public function testIndent_1_MaxErr_10() {}
    public function testIndent_1_MaxErr_20_MaxLen_64_Predef_FooBar() {}
    public function testPredef_Foo_Predef_Bar_Predef_FooBar() {}
    public function testIndent_8_MaxErr_10_MaxLen_26_Predef_Foo_Predef_Bar_Predef_FooBar() {}
    
    
    /**
     * 
     * @return string
     */
    private function getExpectedOptionsString() {
        $optionsString = '';
        
        foreach ($this->getOptionsAndValues() as $optionName => $optionValue) {
            if (is_array($optionValue)) {
                foreach ($optionValue as $optionSubsetValue) {
                    $optionsString .= '--' . $optionName . '=' . $optionSubsetValue . ' ';
                }                
            } else {
                $optionsString .= '--' . $optionName . '=' . $optionValue . ' ';
            }
        }
        
        return trim($optionsString);
    }
    
    
    /**
     * 
     * @return string
     */
    private function getOptionsAndValues() {
        $optionsAndValues = array();        
        
        $rawValues = explode('_', \ICanBoogie\underscore(str_replace('test', '', strtolower($this->getName()))));       
        
        $currentKey = null;
        foreach ($rawValues as $index => $value) {
            if ($index % 2 == 0) {
                $currentKey = $value;
            } else {
                if (isset($optionsAndValues[$currentKey])) {
                    if (!is_array($optionsAndValues[$currentKey])) {
                        $optionsAndValues[$currentKey] = array($optionsAndValues[$currentKey]);
                    }
                    
                    $optionsAndValues[$currentKey][] = $value;                
                } else {
                    $optionsAndValues[$currentKey] = $value;
                }                
            }
        }        
        
        return $optionsAndValues;
    } 
    
}