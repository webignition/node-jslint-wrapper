<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock as HttpMockSubscriber;
use webignition\NodeJslint\Wrapper\Wrapper;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURES_BASE_PATH = '/fixtures';

    /**
     * @var string
     */
    private $fixturePath = null;

    /**
     * @var HttpClient
     */
    private $httpClient = null;

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
        }

        return $this->httpClient;
    }

    /**
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod = null)
    {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . str_replace('\\', '/', $testClass);

        if (is_string($testMethod)) {
            $this->fixturePath .=  '/' . $testMethod;
        }
    }

    /**
     * @return string
     */
    protected function getTestFixturePath()
    {
        return $this->fixturePath;
    }

    /**
     * @param string $fixtureName
     *
     * @return string
     */
    protected function getFixture($fixtureName)
    {
        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . '/' . $fixtureName . '.txt');
    }

    /**
     * @return array
     */
    protected function getAllFlagNames()
    {
        return array(
            'anon' => JsLintFlag::ANON,
            'bitwise' => JsLintFlag::BITWISE,
            'browser' => JsLintFlag::BROWSER,
            'cap' => JsLintFlag::CAP,
            'continue' => JsLintFlag::FLAG_CONTINUE,
            'css' => JsLintFlag::CSS,
            'debug' => JsLintFlag::DEBUG,
            'devel' => JsLintFlag::DEVEL,
            'eqeq' => JsLintFlag::EQEQ,
            'es5' => JsLintFlag::ES5,
            'evil' => JsLintFlag::EVIL,
            'forin' => JsLintFlag::FORIN,
            'fragment' => JsLintFlag::FRAGMENT,
            'newcap' => JsLintFlag::NEWCAP,
            'node' => JsLintFlag::NODE,
            'nomen' => JsLintFlag::NOMEN,
            'on' => JsLintFlag::ON,
            'passfail' => JsLintFlag::PASSFAIL,
            'plusplus' => JsLintFlag::PLUSPLUS,
            'properties' => JsLintFlag::PROPERTIES,
            'regexp' => JsLintFlag::REGEXP,
            'rhino' => JsLintFlag::RHINO,
            'undef' => JsLintFlag::UNDEF,
            'unparam' => JsLintFlag::UNPARAM,
            'sloppy' => JsLintFlag::SLOPPY,
            'stupid' => JsLintFlag::STUPID,
            'sub' => JsLintFlag::SUB,
            'vars' => JsLintFlag::VARS,
            'white' => JsLintFlag::WHITE,
            'widget' => JsLintFlag::WIDGET,
            'windows' => JsLintFlag::WINDOWS
        );
    }

    /**
     * @return Wrapper
     */
    public function getNewWrapper()
    {
        return new Wrapper();
    }

    /**
     * @param array $fixtures
     */
    protected function setHttpFixtures($fixtures)
    {
        $this->getHttpClient()->getEmitter()->attach(
            new HttpMockSubscriber($fixtures)
        );
    }

    /**
     * @param string $rawOutput
     */
    protected function setValidatorRawOutput($rawOutput)
    {
        PHPMockery::mock(
            'webignition\NodeJslint\Wrapper',
            'shell_exec'
        )->andReturn(
            $rawOutput
        );
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }
}
