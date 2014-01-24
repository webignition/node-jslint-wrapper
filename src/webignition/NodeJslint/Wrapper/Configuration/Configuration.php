<?php
namespace webignition\NodeJslint\Wrapper\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

/**
 * 
 */
class Configuration {    
    
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
    
}