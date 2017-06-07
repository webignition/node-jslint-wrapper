<?php
namespace webignition\NodeJslint\Wrapper\Mock;

use webignition\NodeJslint\Wrapper\Wrapper as BaseWrapper;
use webignition\NodeJslint\Wrapper\Mock\LocalProxy\LocalProxy;

/**
 * Mock wrapper that overrides the main wrapper and allows for mock responses
 * from node-jslint
 */
class Wrapper extends BaseWrapper {  
    
    /**
     *
     * @var string
     */
    private $validatorRawOutput = null;
    
    
    /**
     *
     * @var boolean
     */
    private $deferToParentIfNoRawOutput = false;    
    
    
    /**
     *
     * @var boolean
     */
    private $clearRawOutputWhenUsed = false;
    
    
    /**
     * 
     * @param string $rawOutput
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function setValidatorRawOutput($rawOutput) {
        $this->validatorRawOutput = $rawOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return array
     */
    protected function getRawValidatorOutputLines($executableCommand) {        
        if (is_null($this->validatorRawOutput)) {
            if ($this->deferToParentIfNoRawOutput) {
                return parent::getRawValidatorOutputLines($executableCommand);
            } 
            
            return null;
        }
        
        $output = explode("\n", $this->validatorRawOutput);
        
        if ($this->clearRawOutputWhenUsed) {
            $this->validatorRawOutput = null;
        }
        
        return $output;
    }  
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function enableDeferToParentIfNoRawOutput() {
        $this->deferToParentIfNoRawOutput = true;
        return $this;
    }
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function disableDeferToParentIfNoRawOutput() {
        $this->deferToParentIfNoRawOutput = false;
        return $this;
    }
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function enableClearRawOutputWhenUsed() {
        $this->clearRawOutputWhenUsed = true;
        return $this;
    }  
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Mock\Wrapper
     */
    public function disableClearRawOutputWhenUsed() {
        $this->clearRawOutputWhenUsed = false;
        return $this;
    }      
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy
     */
    protected function createLocalProxy() {
        return new LocalProxy();
    }
    
}