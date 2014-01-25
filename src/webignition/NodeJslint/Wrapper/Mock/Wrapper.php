<?php
namespace webignition\NodeJslint\Wrapper\Mock;

use webignition\NodeJslint\Wrapper\Wrapper as BaseWrapper;

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
    protected function getRawValidatorOutputLines() {        
        if (is_null($this->validatorRawOutput)) {
            if ($this->deferToParentIfNoRawOutput) {
                return parent::getRawValidatorOutputLines();
            } 
            
            return null;
        }      
        
        return explode("\n", $this->validatorRawOutput);
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
    
}