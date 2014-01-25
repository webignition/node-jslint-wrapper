<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile;

use webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile\BaseRemoteFileTest;

class CurlErrorTest extends BaseRemoteFileTest {
    
    const URL_TO_LINT = 'http://example.com/example.js';
    
    /**
     *
     * @var \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    private $wrapper;
    
    public function setUp() {        
        $this->wrapper = $this->getNewWrapper();
        $this->wrapper->getConfiguration()->setUrlToLint(self::URL_TO_LINT);
        
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            'CURL/' . $this->getStatusCode().' message'
        )));       
        
        $this->wrapper->getLocalProxy()->getConfiguration()->setBaseRequest($this->getHttpClient()->get());        
        $this->wrapper->enableDeferToParentIfNoRawOutput();
        
        try {
            $this->wrapper->validate();
            $this->fail('CURL '.$this->getStatusCode().' exception not thrown');
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {            
            $this->assertEquals($this->getStatusCode(), $curlException->getErrorNo());
        }         

    }
    
    public function test6() {}
    public function test28() {}
    
    
    /**
     * 
     * @return int
     */
    private function getStatusCode() {
        return (int)str_replace('test', '', $this->getName());
    }    
    
    
}