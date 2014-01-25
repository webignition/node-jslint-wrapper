<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Mock\Wrapper as MockWrapper;
use Guzzle\Http\Client as HttpClient;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {  
    
    const FIXTURES_BASE_PATH = '../../../../fixtures';
    
    /**
     *
     * @var string
     */
    private $fixturePath = null;
    

    /**
     *
     * @var \Guzzle\Http\Client 
     */
    private $httpClient = null; 
    
    
    /**
     * 
     * @return \Guzzle\Http\Client
     */
    protected function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
        }
        
        return $this->httpClient;
    }    
    

    /**
     * 
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod) {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . $testClass . '/' . $testMethod;       
    }    
    
    
    /**
     * 
     * @return string
     */
    protected function getTestFixturePath() {
        return $this->fixturePath;     
    }
    
    
    /**
     * 
     * @param string $fixtureName
     * @return string
     */
    protected function getFixture($fixtureName) {
        if (file_exists($this->getTestFixturePath() . '/' . $fixtureName)) {
            return file_get_contents($this->getTestFixturePath() . '/' . $fixtureName);
        }
        
        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . '/Common/' . $fixtureName);        
    }
    
    
    /**
     * 
     * @return array
     */
    protected function getAllFlagNames() {
        return array(
            'anon' => JsLintFlag::ANON,
            'bitwise' => JsLintFlag::BITWISE,
            'browser' => JsLintFlag::BROWSER,
            'cap' => JsLintFlag::CAP,
            'continue' => JsLintFlag::FLAG_CONTINUE,
            'css' => JsLintFlag::CSS,
            'debug' => JsLintFlag::DEBUG,
            'devel' => JsLintFlag::DEVEL,
            'eqeq' => JsLintFlag::EQEQ,
            'es5' => JsLintFlag::ES5,
            'evil' => JsLintFlag::EVIL,
            'forin' => JsLintFlag::FORIN,
            'fragment' => JsLintFlag::FRAGMENT,
            'newcap' => JsLintFlag::NEWCAP,
            'node' => JsLintFlag::NODE,
            'nomen' => JsLintFlag::NOMEN,
            'on' => JsLintFlag::ON,
            'passfail' => JsLintFlag::PASSFAIL,
            'plusplus' => JsLintFlag::PLUSPLUS,
            'properties' => JsLintFlag::PROPERTIES,
            'regexp' => JsLintFlag::REGEXP,
            'rhino' => JsLintFlag::RHINO,
            'undef' => JsLintFlag::UNDEF,
            'unparam' => JsLintFlag::UNPARAM,
            'sloppy' => JsLintFlag::SLOPPY,
            'stupid' => JsLintFlag::STUPID,
            'sub' => JsLintFlag::SUB,
            'vars' => JsLintFlag::VARS,
            'white' => JsLintFlag::WHITE,
            'widget' => JsLintFlag::WIDGET,
            'windows' => JsLintFlag::WINDOWS
        );        
    }
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function getNewWrapper() {
        return new MockWrapper();
    }
        
    
}