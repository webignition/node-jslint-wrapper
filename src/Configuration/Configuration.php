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

    const CONFIG_KEY_NODE_PATH = 'node-path';
    const CONFIG_KEY_NODE_JSLINT_PATH = 'node-jslint-path';
    const CONFIG_KEY_FLAGS = 'flags';
    const CONFIG_KEY_OPTIONS = 'options';
    const CONFIG_KEY_URL_TO_LINT = 'url-to-lint';

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
     * @var string[]
     */
    private $validFlags;

    /**
     * @var string[]
     */
    private $validOptions;

    /**
     * @param array $configurationValues
     */
    public function __construct($configurationValues = [])
    {
        $this->validFlags = JsLintFlag::getList();
        $this->validOptions = JsLintOption::getList();

        if (!isset($configurationValues[self::CONFIG_KEY_NODE_PATH])) {
            $configurationValues[self::CONFIG_KEY_NODE_PATH] = self::DEFAULT_NODE_PATH;
        }

        if (!isset($configurationValues[self::CONFIG_KEY_NODE_JSLINT_PATH])) {
            $configurationValues[self::CONFIG_KEY_NODE_JSLINT_PATH] = self::DEFAULT_NODE_JSLINT_PATH;
        }

        if (!isset($configurationValues[self::CONFIG_KEY_FLAGS])) {
            $configurationValues[self::CONFIG_KEY_FLAGS] = [];
        }

        if (!isset($configurationValues[self::CONFIG_KEY_OPTIONS])) {
            $configurationValues[self::CONFIG_KEY_OPTIONS] = [];
        }

        $this->nodePath = $configurationValues[self::CONFIG_KEY_NODE_PATH];
        $this->nodeJsLintPath = $configurationValues[self::CONFIG_KEY_NODE_JSLINT_PATH];

        foreach ($configurationValues[self::CONFIG_KEY_FLAGS] as $name => $value) {
            if (in_array($name, $this->validFlags)) {
                $this->flags[$name] = (bool)$value;
            }
        }

        foreach ($configurationValues[self::CONFIG_KEY_OPTIONS] as $name => $value) {
            if (in_array($name, $this->validOptions)) {
                $this->options[$name] = $value;
            }
        }

        if (isset($configurationValues[self::CONFIG_KEY_URL_TO_LINT])) {
            $this->setUrlToLint($configurationValues[self::CONFIG_KEY_URL_TO_LINT]);
        }
    }

    /**
     * @param string $name
     */
    public function unsetFlag($name)
    {
        if (isset($this->flags[$name])) {
            unset($this->flags[$name]);
        }
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
     * @param string $url
     *
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
        if (empty($this->urlToLint)) {
            throw new \UnexpectedValueException('URL to lint not present; set this first with ->setUrlToLint()', 1);
        }

        $commandParts = array(
            $this->nodePath,
            $this->nodeJsLintPath,
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
        if (empty($this->urlToLint)) {
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
        return array_merge([NodeJsLintFlag::JSON => true], $this->getFlags());
    }
}
