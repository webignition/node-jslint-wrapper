<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Message\RequestInterface as HttpRequest;

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
     * @var HttpClient
     */
    private $httpClient = null;


    /**
     * @param HttpClient $httpClient
     * @return $this
     */
    public function setHttpClient(HttpClient $httpClient) {
        $this->httpClient = $httpClient;
        return $this;
    }    
    
    
    /**
     * 
     * @return HttpClient
     */
    public function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient;
        }
        
        return $this->httpClient;
    } 
    

    /**
     * @param $url
     * @return $this
     * @throws \InvalidArgumentException
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