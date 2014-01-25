<?php
namespace webignition\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;


/**
 * 
 */
class Wrapper {
    
    const INVALID_ARGUMENT_EXCEPTION_CONFIGURATION_NOT_SET = 1;  
    
    /**
     *
     * @var Configuration
     */
    private $configuration;
    
    
 
    /**
     * 
     * @param \webignition\NodeJslint\Wrapper\Configuration\Configuration $configuration
     * @return \webignition\NodeJslint\Wrapper\Wrapper
     */
    public function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
        return $this;
    }
    
    

    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }    
    
    
    /**
     * 
     * @return boolean
     */
    public function hasConfiguration() {
        return !is_null($this->getConfiguration());
    }
    
    
    public function validate() {
        if (!$this->hasConfiguration()) {
            throw new \InvalidArgumentException('Unable to validate; configuration not set', self::INVALID_ARGUMENT_EXCEPTION_CONFIGURATION_NOT_SET);
        }           
    }
    
    
}