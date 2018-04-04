<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\CommandFactory;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class CommandFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param Configuration $configuration
     * @param string $urlToLint
     * @param string $expectedExecutableCommand
     */
    public function testCreate(Configuration $configuration, $urlToLint, $expectedExecutableCommand)
    {
        $commandFactory = new CommandFactory($configuration);

        $this->assertEquals($expectedExecutableCommand, $commandFactory->create($urlToLint));
    }

    /**
     * @return array
     */
    public function createDataProvider()
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
                'configuration' => new Configuration(),
                'urlToLint' => 'http://example.com/foo.js',
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix() . 'http://example.com/foo.js 2>&1',
            ],
            'vary node and node-jslint paths' => [
                'configuration' => new Configuration([
                    Configuration::CONFIG_KEY_NODE_PATH => '/foo/node',
                    Configuration::CONFIG_KEY_NODE_JSLINT_PATH => '/foo/nodejslint',
                ]),
                'urlToLint' => 'http://example.com/foo.js',
                'expectedExecutableCommand' =>
                    '/foo/node /foo/nodejslint --json=true http://example.com/foo.js 2>&1',
            ],
            'all flags enabled all options enabled' => [
                'configuration' => new Configuration([
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsEnabled,
                    Configuration::CONFIG_KEY_OPTIONS => $allOptionsSet,
                ]),
                'urlToLint' => 'http://example.com/foo.js',
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix()
                    . $this->createExpectedFlagsCommandString($allFlagsEnabled)
                    . ' '
                    . $this->createExpectedOptionsCommandString($allOptionsSet)
                    . ' http://example.com/foo.js 2>&1'
                ,
            ],
            'all flags disabled' => [
                'configuration' => new Configuration([
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsDisabled,
                ]),
                'urlToLint' => 'http://example.com/foo.js',
                'expectedExecutableCommand' =>
                    $this->getDefaultExecutableCommandPrefix()
                    . $this->createExpectedFlagsCommandString($allFlagsDisabled)
                    . ' http://example.com/foo.js 2>&1'
            ],
            'url to lint: http' => [
                'configuration' => new Configuration(),
                'urlToLint' => 'http://example.com/foo.js',
                $this->getDefaultExecutableCommandPrefix() . 'http://example.com/foo.js 2>&1',
            ],
            'url to lint: https' => [
                'configuration' => new Configuration(),
                'urlToLint' => 'https://example.com/foo.js',
                $this->getDefaultExecutableCommandPrefix() . 'https://example.com/foo.js 2>&1',
            ],
            'url to lint: file' => [
                'configuration' => new Configuration(),
                'urlToLint' => 'file:/foo.js',
                $this->getDefaultExecutableCommandPrefix() . '/foo.js 2>&1',
            ],
            'url to lint: local file missing scheme' => [
                'configuration' => new Configuration(),
                'urlToLint' => 'file:/foo.js',
                $this->getDefaultExecutableCommandPrefix() . '/foo.js 2>&1',
            ],
            'jslint predef array of values' => [
                'configuration' => new Configuration([
                    Configuration::CONFIG_KEY_OPTIONS => [
                        JsLintOption::PREDEF => [
                            'predef-foo',
                            'predef-bar',
                        ],
                    ],
                ]),
                'urlToLint' => 'file:/foo.js',
                $this->getDefaultExecutableCommandPrefix() . '--predef=predef-foo --predef=predef-bar /foo.js 2>&1',
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
