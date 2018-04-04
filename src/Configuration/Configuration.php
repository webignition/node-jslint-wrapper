<?php

namespace webignition\NodeJslint\Wrapper\Configuration;

use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class Configuration
{
    const DEFAULT_NODE_PATH = '/usr/bin/node';
    const DEFAULT_NODE_JSLINT_PATH = '/usr/share/node-jslint/node_modules/jslint/bin/jslint.js';

    const CONFIG_KEY_NODE_PATH = 'node-path';
    const CONFIG_KEY_NODE_JSLINT_PATH = 'node-jslint-path';
    const CONFIG_KEY_FLAGS = 'flags';
    const CONFIG_KEY_OPTIONS = 'options';

    /**
     * Full path to node executable
     *
     * @var string
     */
    private $nodePath = self::DEFAULT_NODE_PATH;

    /**
     * Full path to node-jstlint module's jslint.js
     *
     * @var string
     */
    private $nodeJsLintPath = self::DEFAULT_NODE_JSLINT_PATH;

    /**
     * @var bool[]
     */
    private $flags = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param array $configurationValues
     */
    public function __construct($configurationValues = [])
    {
        $validFlags = JsLintFlag::getList();
        $validOptions = JsLintOption::getList();

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
            if (in_array($name, $validFlags)) {
                $this->flags[$name] = (bool)$value;
            }
        }

        foreach ($configurationValues[self::CONFIG_KEY_OPTIONS] as $name => $value) {
            if (in_array($name, $validOptions)) {
                $this->options[$name] = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getNodePath()
    {
        return $this->nodePath;
    }

    /**
     * @return string
     */
    public function getNodeJsLintPath()
    {
        return $this->nodeJsLintPath;
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
     * @return bool[]
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
}
