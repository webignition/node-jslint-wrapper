<?php

namespace webignition\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\NodeJsLint as NodeJsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class CommandFactory
{
    const FILE_URL_PREFIX = 'file:';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $urlToLint
     *
     * @return string
     */
    public function create($urlToLint)
    {
        $commandParts = [
            $this->configuration->getNodePath(),
            $this->configuration->getNodeJsLintPath(),
            $this->createFlagsString(),
            $this->createOptionsString(),
            $this->createPathToLint($urlToLint),
            '2>&1'
        ];

        return str_replace('  ', ' ', implode(' ', $commandParts));
    }

    /**
     * @return string
     */
    private function createOptionsString()
    {
        $optionStrings = [];

        foreach ($this->configuration->getOptions() as $name => $value) {
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
     * @return string
     */
    private function createFlagsString()
    {
        $flagStrings = [];

        $flags = array_merge(
            [NodeJsLintFlag::JSON => true],
            $this->configuration->getFlags()
        );

        foreach ($flags as $name => $value) {
            $flagStrings[] = '--' . $name . '=' . (($value) ?  'true' : 'false');
        }

        return implode(' ', $flagStrings);
    }

    /**
     * @param string $urlToLint
     *
     * @return string
     */
    private function createPathToLint($urlToLint)
    {
        if (FileUrlDetector::isFileUrl($urlToLint)) {
            return substr($urlToLint, strlen(self::FILE_URL_PREFIX));
        }

        return $urlToLint;
    }
}
