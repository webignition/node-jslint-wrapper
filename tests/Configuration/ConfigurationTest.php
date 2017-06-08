<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\Flag;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createConfigurationDataProvider
     *
     * @param array $configurationValues
     * @param string $expectedUrlToLint
     * @param array $expectedFlags
     * @param array $expectedOptions
     * @param string $expectedExecutableCommand
     */
    public function testCreateConfiguration(
        $configurationValues,
        $expectedUrlToLint,
        $expectedFlags,
        $expectedOptions,
        $expectedExecutableCommand
    ) {
        $configuration = new Configuration($configurationValues);


        $this->assertEquals($expectedUrlToLint, $configuration->getUrlToLint());
        $this->assertEquals($expectedFlags, $configuration->getFlags());
        $this->assertEquals($expectedOptions, $configuration->getOptions());
        $this->assertEquals($expectedExecutableCommand, $configuration->getExecutableCommand());
    }

    /**
     * @return array
     */
    public function createConfigurationDataProvider()
    {
        $allFlags = JsLintFlag::getList();
        $allFlagsEnabled = [];
        $allFlagsDisabled = [];

        $allOptionsSet = [
            JSLintOPtion::INDENT => 12,
            JSLintOPtion::MAXERR => 99,
            JSLintOPtion::MAXLEN => 15,
            JSLintOPtion::PREDEF => 'window',
        ];

        foreach ($allFlags as $name) {
            $allFlagsEnabled[$name] = true;
            $allFlagsDisabled[$name] = false;
        }

        return [
            'defaults' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'http://example.com/foo.js',
                ],
                'expectedUrlToLint' => 'http://example.com/foo.js',
                'expectedFlags' => [],
                'expectedOptions' => [],
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix() . 'http://example.com/foo.js 2>&1',
            ],
            'vary node and node-jslint paths' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'http://example.com/foo.js',
                    Configuration::CONFIG_KEY_NODE_PATH => '/foo/node',
                    Configuration::CONFIG_KEY_NODE_JSLINT_PATH => '/foo/nodejslint',
                ],
                'expectedUrlToLint' => 'http://example.com/foo.js',
                'expectedFlags' => [],
                'expectedOptions' => [],
                'expectedExecutableCommand' =>
                    '/foo/node /foo/nodejslint --json=true http://example.com/foo.js 2>&1',
            ],
            'all flags enabled all options enabled' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'http://example.com/foo.js',
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsEnabled,
                    Configuration::CONFIG_KEY_OPTIONS => $allOptionsSet,
                ],
                'expectedUrlToLint' => 'http://example.com/foo.js',
                'expectedFlags' => $allFlagsEnabled,
                'expectedOptions' => $allOptionsSet,
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix()
                    . $this->createExpectedFlagsCommandString($allFlagsEnabled)
                    . ' '
                    . $this->createExpectedOptionsCommandString($allOptionsSet)
                    . ' http://example.com/foo.js 2>&1'
                ,
            ],
            'all flags disabled' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'http://example.com/foo.js',
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsDisabled,
                ],
                'expectedUrlToLint' => 'http://example.com/foo.js',
                'expectedFlags' => $allFlagsDisabled,
                'expectedOptions' => [],
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix()
                    . $this->createExpectedFlagsCommandString($allFlagsDisabled)
                    . ' http://example.com/foo.js 2>&1'
            ],
            'url to lint: http' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'http://example.com/foo.js',
                ],
                'expectedUrlToLint' => 'http://example.com/foo.js',
                'expectedFlags' => [],
                'expectedOptions' => [],
                $this->getDefaultExecutableCommandPrefix() . 'http://example.com/foo.js 2>&1',
            ],
            'url to lint: https' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'https://example.com/foo.js',
                ],
                'expectedUrlToLint' => 'https://example.com/foo.js',
                'expectedHasFlags' => [],
                'expectedOptions' => [],
                $this->getDefaultExecutableCommandPrefix() . 'https://example.com/foo.js 2>&1',
            ],
            'url to lint: file' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => 'file:/foo.js',
                ],
                'expectedUrlToLint' => 'file:/foo.js',
                'expectedFlags' => [],
                'expectedOptions' => [],
                $this->getDefaultExecutableCommandPrefix() . '/foo.js 2>&1',
            ],
            'url to lint: local file missing scheme' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_URL_TO_LINT => '/foo.js',
                ],
                'expectedUrlToLint' => 'file:/foo.js',
                'expectedFlags' => [],
                'expectedOptions' => [],
                $this->getDefaultExecutableCommandPrefix() . '/foo.js 2>&1',
            ],
        ];
    }

    /**
     * @return string
     */
    private function getDefaultExecutableCommandPrefix()
    {
        return '/usr/bin/node /usr/share/node-jslint/node_modules/jslint/bin/jslint.js --json=true ';
    }

    /**
     * @param array $flags
     *
     * @return string
     */
    private function createExpectedFlagsCommandString($flags)
    {
        $flagCommandStrings = [];

        foreach ($flags as $name => $value) {
            $flagCommandStrings[] = '--' . $name . '=' . ($value ? 'true' : 'false');
        }

        return implode(' ', $flagCommandStrings);
    }

    /**
     * @param $options
     *
     * @return string
     */
    private function createExpectedOptionsCommandString($options)
    {
        $optionsCommandStrings = [];

        foreach ($options as $name => $value) {
            $optionsCommandStrings[] = '--' . $name . '=' . $value;
        }

        return implode(' ', $optionsCommandStrings);
    }
}
