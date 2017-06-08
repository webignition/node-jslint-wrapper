<?php
namespace webignition\NodeJslint\Wrapper\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Flag\NodeJsLint as NodeJsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class Configuration
{
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
     * @var boolean[]
     */
    private $flags = array();

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $urlToLint = null;

    /**
     * @param string $nodePath
     */
    public function setNodePath($nodePath)
    {
        $this->nodePath = $nodePath;
    }

    /**
     * @return string
     */
    public function getNodePath()
    {
        return (is_null($this->nodePath)) ? self::DEFAULT_NODE_PATH : $this->nodePath;
    }

    /**
     * @param string $nodeJslintPath
     */
    public function setNodeJslintPath($nodeJslintPath)
    {
        $this->nodeJsLintPath = $nodeJslintPath;
    }

    /**
     * @return string
     */
    public function getNodeJslintPath()
    {
        return (is_null($this->nodeJsLintPath)) ? self::DEFAULT_NODE_JSLINT_PATH : $this->nodeJsLintPath;
    }

    /**
     * @param string $name
     */
    public function enableFlag($name)
    {
        if (!$this->isValidFlag($name)) {
            throw new \InvalidArgumentException('Flag "'.$name.'" is not valid', 1);
        }

        $this->flags[$name] = true;
    }

    /**
     * @param string $name
     */
    public function disableFlag($name)
    {
        if (!$this->isValidFlag($name)) {
            throw new \InvalidArgumentException('Flag "'.$name.'" is not valid', 1);
        }

        $this->flags[$name] = false;
    }

    /**
     * @param string $name
     *
     * @return \webignition\NodeJslint\Wrapper\Configuration\Configuration
     */
    public function unsetFlag($name)
    {
        if (isset($this->flags[$name])) {
            unset($this->flags[$name]);
        }
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasFlag($name)
    {
        return isset($this->flags[$name]);
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    private function isValidFlag($name)
    {
        return in_array($name, JsLintFlag::getList());
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     */
    public function setOption($name, $value)
    {
        if (!$this->isValidOption($name)) {
            throw new \InvalidArgumentException('Flag "'.$name.'" is not valid', 1);
        }

        $this->options[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    private function isValidOption($name)
    {
        return in_array($name, JsLintOption::getList());
    }

    /**
     * @param string $url
     */
    public function setUrlToLint($url)
    {
        if (!$this->urlHasExpectedScheme($url) && $url[0] == '/') {
            $url = 'file:' . $url;
        }

        $this->urlToLint = $url;
    }

    /**
     *
     * @param string $url
     * @return boolean
     */
    private function urlHasExpectedScheme($url)
    {
        $expectedSchemes = array(
            'http:',
            'https:',
            'file:'
        );

        foreach ($expectedSchemes as $scheme) {
            if (substr($url, 0, strlen($scheme)) == $scheme) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUrlToLint()
    {
        return $this->urlToLint;
    }

    /**
     * @return boolean
     */
    public function hasUrlToLint()
    {
        return !is_null($this->urlToLint);
    }

    /**
     * @return boolean[]
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getExecutableCommand()
    {
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
     * @return string
     */
    private function getExecutableCommandPathToLint()
    {
        if ($this->hasFileUrlToLint()) {
            return substr($this->getUrlToLint(), strlen(self::FILE_URL_PREFIX));
        }

        return $this->getUrlToLint();
    }

    /**
     * @return boolean
     */
    public function hasFileUrlToLint()
    {
        if (!$this->hasUrlToLint()) {
            return false;
        }

        return substr($this->getUrlToLint(), 0, strlen(self::FILE_URL_PREFIX)) == self::FILE_URL_PREFIX;
    }

    /**
     * @return string
     */
    private function getExecutableCommandFlagsString()
    {
        $flagStrings = array();

        foreach ($this->getExecutableCommandFlags() as $name => $value) {
            $flagStrings[] = '--' . $name . '=' . (($value) ?  'true' : 'false');
        }

        return implode(' ', $flagStrings);
    }

    /**
     * @return string
     */
    private function getExecutableCommandOptionsString()
    {
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
     * @return boolean[]
     */
    private function getExecutableCommandFlags()
    {
        $flags = $this->getFlags();
        $flags[NodeJsLintFlag::JSON] = true;

        return $flags;
    }
}
