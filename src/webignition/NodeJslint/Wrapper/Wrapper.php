<?php
namespace webignition\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy;
use webignition\NodeJslintOutput\Parser as OutputParser;

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
     * @var LocalProxy
     */
    private $localProxy;    
    

    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }
        
        return $this->configuration;
    }    
    
    
    /**
     * 
     * @return boolean
     */
    public function hasConfiguration() {
        return !is_null($this->getConfiguration());
    }
    
    
    /**
     * 
     * @throws \InvalidArgumentException
     * @throws \webignition\NodeJslintOutput\Exception
     * @return \webignition\NodeJslintOutput\NodeJslintOutput
     */
    public function validate() {
        if (!$this->hasConfiguration()) {
            throw new \InvalidArgumentException('Unable to validate; configuration not set', self::INVALID_ARGUMENT_EXCEPTION_CONFIGURATION_NOT_SET);
        }
        
        $validatorOutput = $this->getRawValidatorOutputLines();
        if (!$this->getConfiguration()->hasFileUrlToLint()) {            
            $this->getLocalProxy()->clearLocalRemoteResource();
        }
        
        $outputParser = new OutputParser();
        
        /* @var $output \webignition\NodeJslintOutput\NodeJslintOutput */
        $output = $outputParser->parse(implode("\n", $validatorOutput));
        
        if (!$this->getConfiguration()->hasFileUrlToLint()) {            
            $this->replaceLocalStatusLineWithRemoteStatusLine($output);
        }
        
        return $output;
    }
    
    private function replaceLocalStatusLineWithRemoteStatusLine(\webignition\NodeJslintOutput\NodeJslintOutput $output) {
        $output->setStatusLine($this->getConfiguration()->getUrlToLint());
    }
    
    
    /**
     * 
     * @return string[]
     */
    protected function getRawValidatorOutputLines() {        
        $validatorOutputLines = array();
        
        if ($this->getConfiguration()->hasFileUrlToLint()) {
            $executableCommand = $this->getConfiguration()->getExecutableCommand();
        } else {
            $this->getLocalProxy()->getConfiguration()->setUrlToLint($this->getConfiguration()->getUrlToLint());
            $executableCommand = $this->getExecutableCommandForRemoteResource();
        }        
        
        exec($executableCommand, $validatorOutputLines);
        return $validatorOutputLines;        
    }
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy
     */
    public function getLocalProxy() {
        if (is_null($this->localProxy)) {
            $this->localProxy = $this->createLocalProxy();
        }
        
        return $this->localProxy;
    }
    
    
    /**
     * 
     * @return \webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy
     */
    protected function createLocalProxy() {
        return new LocalProxy();
    }
    
    
    /**
     * 
     * @param \webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy $localProxy
     */
    protected function setLocalProxy(LocalProxy $localProxy) {
        $this->localProxy = $localProxy;
    }
    

    /**
     * 
     * @return string
     */
    private function getExecutableCommandForRemoteResource() {
        return str_replace($this->getConfiguration()->getUrlToLint(), $this->getLocalProxy()->getLocalRemoteResourcePath(), $this->getConfiguration()->getExecutableCommand());      
    }   
    
}