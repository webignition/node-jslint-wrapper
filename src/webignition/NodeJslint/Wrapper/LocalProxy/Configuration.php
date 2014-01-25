<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

/**
 * 
 */
class Configuration {
    
    
    /**
     *
     * @var string
     */
    private $urlToLint = null;    
    
    
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
    
    
    /**
     * 
     * @param string $url
     */
    public function setUrlToLint($url) {
        if (!$this->urlHasExpectedScheme($url)) {
            throw new \InvalidArgumentException('Url "'.$url.'" is not valid', 1);
        }
        
        $this->urlToLint = $url;
        return $this;
    }
    
    
    /**
     * 
     * @param string $url
     * @return boolean
     */
    private function urlHasExpectedScheme($url) {
        $expectedSchemes = array(
            'http://',
            'https://',
        ); 
        
        foreach ($expectedSchemes as $scheme) {
            if (substr($url, 0, strlen($scheme)) == $scheme) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getUrlToLint() {
        return $this->urlToLint;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasUrlToLint() {
        return !is_null($this->urlToLint);
    }
    
}