<?php
namespace webignition\NodeJslint\Wrapper\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Flag\NodeJsLint as NodeJsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

/**
 * 
 */
class Configuration {    
  
    const FILE_URL_PREFIX = 'file:';
    
    const DEFAULT_NODE_PATH = '/usr/bin/node';
    const DEFAULT_NODE_JSLINT_PATH = '/usr/share/node-jslint/node_modules/jslint/bin/jslint.js';    
    
    
    /**
     * Full path to node executable
     * 
     * @var string
     */
    private $nodePath = null;
    
    /**
     * Full path to node-jstlint module's jslint.js
     * 
     * @var string
     */
    private $nodeJsLintPath = null;    
    
    /**
     *
     * @var boolean[]
     */
    private $flags = array();
    
    
    /**
     *
     * @var array
     */
    private $options = array();
    
    
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
     * @param string $nodePath
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function setNodePath($nodePath) {
        $this->nodePath = $nodePath;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getNodePath() {
        return (is_null($this->nodePath)) ? self::DEFAULT_NODE_PATH : $this->nodePath;
    }
    
    
    /**
     * 
     * @param string $nodeJslintPath
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function setNodeJslintPath($nodeJslintPath) {
        $this->nodeJsLintPath = $nodeJslintPath;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */    
    public function getNodeJslintPath() {
        return (is_null($this->nodeJsLintPath)) ? self::DEFAULT_NODE_JSLINT_PATH : $this->nodeJsLintPath;
    }
    
    
    /**
     * 
     * @param string $name
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function setFlag($name) {
        if (!$this->isValidFlag($name)) {
            throw new \InvalidArgumentException('Flag "'.$name.'" is not valid', 1);
        }
        
        $this->flags[$name] = true;          
        return $this;
    }
    
    
    /**
     * 
     * @param string $name
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function unsetFlag($name) {
        if (isset($this->flags[$name])) {
            unset($this->flags[$name]);
        }
        
        return $this;
    }
    
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function hasFlag($name) {
        return isset($this->flags[$name]);
    }
    
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    private function isValidFlag($name) {
        return in_array($name, JsLintFlag::getList());
    }
    
    
    /**
     * 
     * @param string $name
     * @param mixed $value
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     * @throws \InvalidArgumentException
     */
    public function setOption($name, $value) {
        if (!$this->isValidOption($name)) {
            throw new \InvalidArgumentException('Flag "'.$name.'" is not valid', 1);
        }
        
        $this->options[$name] = $value;
        return $this;
    }
    

    /**
     * 
     * @param string $name
     * @return boolean
     */    
    private function isValidOption($name) {
        return in_array($name, JsLintOption::getList());
    } 
    
    
    /**
     * 
     * @param string $url
     */
    public function setUrlToLint($url) {
        $this->urlToLint = $url;
        return $this;
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
    
    
    /**
     * 
     * @return boolean[]
     */
    public function getFlags() {
        return $this->flags;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }
    
    
    /**
     * 
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getExecutableCommand() {
        if (!$this->hasUrlToLint()) {
            throw new \UnexpectedValueException('URL to lint not present; set this first with ->setUrlToLint()', 1);
        }
        
        $commandParts = array(
            $this->getNodePath(),
            $this->getNodeJslintPath(),
            $this->getExecutableCommandFlagsString(),
            $this->getExecutableCommandOptionsString(),
            $this->getExecutableCommandPathToLint(),
            '2>&1'
        );
        
        return str_replace('  ', ' ', implode(' ', $commandParts));
    }
    
    
    /**
     * 
     * @return string
     */
    private function getExecutableCommandPathToLint() {
        if ($this->hasFileUrlToLint()) {
            return substr($this->getUrlToLint(), strlen(self::FILE_URL_PREFIX));
        }
        
        return $this->getUrlToLint();
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasFileUrlToLint() {
        if (!$this->hasUrlToLint()) {
            return false;
        }
        
        return substr($this->getUrlToLint(), 0, strlen(self::FILE_URL_PREFIX)) == self::FILE_URL_PREFIX;
    }
    
    
    
    /**
     * 
     * @return string
     */
    private function getExecutableCommandFlagsString() {
        $flagNames = array_keys($this->getExecutableCommandFlags());
        foreach ($flagNames as $index => $name) {
            $flagNames[$index] = '--' . $name;
        }

        return implode(' ', $flagNames);
    }
    
    
    /**
     * 
     * @return string
     */
    private function getExecutableCommandOptionsString() {
        $optionStrings = array();       
     
        foreach ($this->getOptions() as $name => $value) {
            if ($name === JsLintOption::PREDEF && is_array($value)) {
                foreach ($value as $prefValue) {
                    $optionStrings[] = '--' . $name . '=' . $prefValue;
                }
            } else {
                $optionStrings[] = '--' . $name . '=' . $value;
            }                                          
        }
        
        return implode(' ', $optionStrings);        
    }
    
    
    
    /**
     * 
     * @return boolean[]
     */
    private function getExecutableCommandFlags() {
        $flags = $this->getFlags();
        $flags[NodeJsLintFlag::JSON] = true;
        
        return $flags;
    }  
    
    
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