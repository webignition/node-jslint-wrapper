<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

use webignition\NodeJslint\Wrapper\LocalProxy\Configuration;

/**
 * node-jslint can only be run against local files (such as /home/example/script.js)
 * This LocalProxy handles the retrieval and local-storage of remote resources
 * to make the linting of remote resources transparent at the wrapper level
 */
class LocalProxy { 
    
    const MAX_REMOTE_RESOURCE_AGE = 60;
    
    
    /**
     *
     * @var \webignition\WebResource\Service\Service
     */
    private $webResourceService;     
    
    
    /**
     *
     * @var string
     */
    private $localRemoteResourcePath = null;
    
    
    /**
     *
     * @var \webignition\NodeJslint\Wrapper\LocalProxy\Configuration
     */
    private $configuration = null;
    
    
    /**
     * 
     * @return \webignition\WebResource\Service\Service
     */
    public function getWebResourceService() {
        if (is_null($this->webResourceService)) {
            $this->webResourceService = new \webignition\WebResource\Service\Service();
            $this->webResourceService->getConfiguration()->setContentTypeWebResourceMap(array(
                'text/javascript' => 'webignition\WebResource\WebResource',
                'application/javascript' => 'webignition\WebResource\WebResource',
                'application/x-javascript' => 'webignition\WebResource\WebResource'
            ));
            $this->webResourceService->getConfiguration()->disableAllowUnknownResourceTypes();
        }
        
        return $this->webResourceService;
    }

    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\LocalProxy\Configuration
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }
        
        return $this->configuration;
    }  
    
    
    /**
     * 
     * @return \webignition\WebResource\WebResource
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    private function retrieveRemoteResource() {
        $request = clone $this->getConfiguration()->getBaseRequest();            
        $request->setUrl($this->getConfiguration()->getUrlToLint());

        return $this->getWebResourceService()->get($request);        
    }
    
    
    /**
     * 
     * @return string
     */
    public function getLocalRemoteResourcePath() {
        if (is_null($this->localRemoteResourcePath)) {
            $this->localRemoteResourcePath = sys_get_temp_dir() . '/' . $this->getUrlToLintHash() . '.' . $this->getLocalRemoteResourcePathTimestamp() . '.js';
            
            if ($this->isLocalRemoteResourceStale()) {
                $resource = $this->retrieveRemoteResource();
                @file_put_contents($this->localRemoteResourcePath, $resource->getContent());
            }
        }
        
        return $this->localRemoteResourcePath;
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getLocalRemoteResourcePathTimestamp() {
        return (string)microtime(true);
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasLocalRemoteResource() {
        return @file_exists($this->getLocalRemoteResourcePath());
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isLocalRemoteResourceStale() {
        return true;
        
        if (!$this->hasLocalRemoteResource()) {
            return true;
        }
        
        return time() - filemtime($this->localRemoteResourcePath) > self::MAX_REMOTE_RESOURCE_AGE;
    }
    
    
    /**
     * 
     * @return string
     */
    private function getUrlToLintHash() {
        if (!$this->getConfiguration()->hasUrlToLint()) {
            throw new \RuntimeException('Url to lint has not been set', 1);
        }
        
        return md5($this->getConfiguration()->getUrlToLint());
    } 
    
    
    public function clearLocalRemoteResource() {
        @unlink($this->localRemoteResourcePath);
        $this->localRemoteResourcePath = null;
    }
    
    
}