<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

/**
 * 
 */
class Configuration {
    
    
    /**
     *
     * @var \Guzzle\Http\Message\Request
     */
    private $baseRequest = null;

    
    /**
     * 
     * @param \Guzzle\Http\Message\Request $request
     * @return \webignition\CssValidatorWrapper\Configuration
     */
    public function setBaseRequest(\Guzzle\Http\Message\Request $request) {
        $this->baseRequest = $request;
        return $this;
    }    
    
    
    /**
     * 
     * @return \Guzzle\Http\Message\Request $request
     */
    public function getBaseRequest() {
        if (is_null($this->baseRequest)) {
            $client = new \Guzzle\Http\Client;            
            $this->baseRequest = $client->get();
        }
        
        return $this->baseRequest;
    }  
    
    
    
    
    
}