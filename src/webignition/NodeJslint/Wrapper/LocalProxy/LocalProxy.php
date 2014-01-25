<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

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
    
    
//    /**
//     * 
//     * @return string
//     */
//    private function getExecutableCommandPathToLint() {
//        if ($this->hasFileUrlToLint()) {
//            return substr($this->getUrlToLint(), strlen(self::FILE_URL_PREFIX));
//        }
//        
//        return $this->getUrlToLint();
//        
//        if ($this->isLocalRemoteResourceStale()) {
//            $webResource = $this->retrieveRemoteResource();
//            file_put_contents($this->getLocalRemoteResourcePath(), $webResource->getContent());        
//        }
//
//        
//        return $this->getLocalRemoteResourcePath();
//        
//        // $cssValidatorWrapper->getConfiguration()->getWebResourceService()->getConfiguration()->enableRetryWithUrlEncodingDisabled();
//        
//        //return $this->getUrlToLint();
//    }    
    
    
    /**
     * 
     * @return \webignition\WebResource\WebResource
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    private function retrieveRemoteResource() {
        $request = clone $this->getBaseRequest();            
        $request->setUrl($this->getUrlToLint());

        return $this->getWebResourceService()->get($request);        
    }
    
    
    /**
     * 
     * @return string
     */
    private function getLocalRemoteResourcePath() {
        if (is_null($this->localRemoteResourcePath)) {
            $this->localRemoteResourcePath = sys_get_temp_dir() . '/' . $this->getUrlToLintHash() . '.' . microtime(true) . '.js';
        }
        
        return $this->localRemoteResourcePath;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasLocalRemoteResource() {
        return @file_exists($this->getLocalRemoteResourcePath());
    }
    
    
    private function isLocalRemoteResourceStale() {
        if (!$this->hasLocalRemoteResource()) {
            return true;
        }
        
        var_dump("cp01");
        exit();
    }
    
    
    /**
     * 
     * @return string
     */
    private function getUrlToLintHash() {
        return md5($this->getUrlToLint());
    }    
    
    
}