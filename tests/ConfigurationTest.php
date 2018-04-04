<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint;
use webignition\NodeJslint\Wrapper\Configuration\Option\JsLint as JsLintOption;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array $configurationValues
     * @param string $expectedNodePath
     * @param string $expectedNodeJsLintPath
     * @param array $expectedFlags
     * @param array $expectedOptions
     */
    public function testCreate(
        array $configurationValues,
        $expectedNodePath,
        $expectedNodeJsLintPath,
        array $expectedFlags,
        array $expectedOptions
    ) {
        $configuration = new Configuration($configurationValues);

        $this->assertEquals($expectedNodePath, $configuration->getNodePath());
        $this->assertEquals($expectedNodeJsLintPath, $configuration->getNodeJsLintPath());
        $this->assertEquals($expectedFlags, $configuration->getFlags());
        $this->assertEquals($expectedOptions, $configuration->getOptions());
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
                'configurationValues' => [],
                'expectedNodePath' => Configuration::DEFAULT_NODE_PATH,
                'expectedNodeJsLintPath' => Configuration::DEFAULT_NODE_JSLINT_PATH,
                'expectedFlags' => [],
                'expectedOptions' => [],
            ],
            'vary node and node-jslint paths' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_NODE_PATH => '/foo/node',
                    Configuration::CONFIG_KEY_NODE_JSLINT_PATH => '/foo/nodejslint',
                ],
                'expectedNodePath' => '/foo/node',
                'expectedNodeJsLintPath' => '/foo/nodejslint',
                'expectedFlags' => [],
                'expectedOptions' => [],
            ],
            'all flags enabled all options enabled' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsEnabled,
                    Configuration::CONFIG_KEY_OPTIONS => $allOptionsSet,
                ],
                'expectedNodePath' => Configuration::DEFAULT_NODE_PATH,
                'expectedNodeJsLintPath' => Configuration::DEFAULT_NODE_JSLINT_PATH,
                'expectedFlags' => $allFlagsEnabled,
                'expectedOptions' => $allOptionsSet,
            ],
            'all flags disabled' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_FLAGS => $allFlagsDisabled,
                ],
                'expectedNodePath' => Configuration::DEFAULT_NODE_PATH,
                'expectedNodeJsLintPath' => Configuration::DEFAULT_NODE_JSLINT_PATH,
                'expectedFlags' => $allFlagsDisabled,
                'expectedOptions' => [],
            ],
            'jslint predef array of values' => [
                'configurationValues' => [
                    Configuration::CONFIG_KEY_OPTIONS => [
                        JsLintOption::PREDEF => [
                            'predef-foo',
                            'predef-bar',
                        ],
                    ],
                ],
                'expectedNodePath' => Configuration::DEFAULT_NODE_PATH,
                'expectedNodeJsLintPath' => Configuration::DEFAULT_NODE_JSLINT_PATH,
                'expectedFlags' => [],
                'expectedOptions' => [
                    'predef' => [
                        'predef-foo',
                        'predef-bar',
                    ],
                ],
            ],
        ];
    }

    public function testUnsetFlag()
    {
        $configuration = new Configuration([
            Configuration::CONFIG_KEY_FLAGS => [
                JsLint::ANON => true,
            ],
        ]);

        $this->assertEquals([
            JsLint::ANON => true,
        ], $configuration->getFlags());

        $configuration->unsetFlag(JsLint::ANON);

        $this->assertEmpty($configuration->getFlags());
    }
}
