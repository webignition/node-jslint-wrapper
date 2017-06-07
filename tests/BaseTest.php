<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock as HttpMockSubscriber;
use GuzzleHttp\Message\MessageFactory as HttpMessageFactory;
use GuzzleHttp\Message\ResponseInterface as HttpResponse;
use GuzzleHttp\Message\Request as HttpRequest;
use GuzzleHttp\Exception\ConnectException;
use webignition\NodeJslint\Wrapper\Wrapper;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {

    const FIXTURES_BASE_PATH = '/fixtures';

    /**
     *
     * @var string
     */
    private $fixturePath = null;


    /**
     *
     * @var HttpClient
     */
    private $httpClient = null;


    /**
     *
     * @return HttpClient
     */
    protected function getHttpClient() {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
        }

        return $this->httpClient;
    }


    /**
     *
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod = null) {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . str_replace('\\', '/', $testClass);

        if (is_string($testMethod)) {
            $this->fixturePath .=  '/' . $testMethod;
        }
    }


    /**
     *
     * @return string
     */
    protected function getTestFixturePath() {
        return $this->fixturePath;
    }


    /**
     *
     * @param string $fixtureName
     * @return string
     */
    protected function getFixture($fixtureName) {
        if (file_exists($this->getTestFixturePath() . '/' . $fixtureName)) {
            return file_get_contents($this->getTestFixturePath() . '/' . $fixtureName);
        }

        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . '/Common/' . $fixtureName);
    }


    /**
     *
     * @return array
     */
    protected function getAllFlagNames() {
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
    public function getNewWrapper() {
        return new Wrapper();
    }


    protected function setHttpFixtures($fixtures) {
        $this->getHttpClient()->getEmitter()->attach(
            new HttpMockSubscriber($fixtures)
        );
    }


    protected function getHttpFixtures($path, $filter = null) {
        $items = array();

        $fixturesDirectory = new \DirectoryIterator($path);
        $fixturePaths = array();
        foreach ($fixturesDirectory as $directoryItem) {
            if ($directoryItem->isFile() && ((!is_array($filter)) || (is_array($filter) && in_array($directoryItem->getFilename(), $filter)))) {
                $fixturePaths[] = $directoryItem->getPathname();
            }
        }

        sort($fixturePaths);

        foreach ($fixturePaths as $fixturePath) {
            $items[] = file_get_contents($fixturePath);
        }

        return $this->buildHttpFixtureSet($items);
    }


    /**
     *
     * @param array $items Collection of http messages and/or curl exceptions
     * @return array
     */
    protected function buildHttpFixtureSet($items) {
        $fixtures = array();

        foreach ($items as $item) {
            switch ($this->getHttpFixtureItemType($item)) {
                case 'httpMessage':
                    $fixtures[] = $this->getHttpResponseFromMessage($item);
                    break;

                case 'curlException':
                    $fixtures[] = $this->getCurlExceptionFromCurlMessage($item);
                    break;

                default:
                    throw new \LogicException();
            }
        }

        return $fixtures;
    }


    /**
     *
     * @param string $item
     * @return string
     */
    private function getHttpFixtureItemType($item) {
        if (substr($item, 0, strlen('HTTP')) == 'HTTP') {
            return 'httpMessage';
        }

        return 'curlException';
    }


    /**
     *
     * @param string $curlMessage
     * @return ConnectException
     */
    private function getCurlExceptionFromCurlMessage($curlMessage) {
        $curlMessageParts = explode(' ', $curlMessage, 2);

        return new ConnectException(
            'cURL error ' . str_replace('CURL/', '', $curlMessageParts[0]) . ': ' . $curlMessageParts[1],
            new HttpRequest('GET', 'http://example.com/')
        );
    }


    /**
     * @param $message
     * @return HttpResponse
     */
    protected function getHttpResponseFromMessage($message) {
        $factory = new HttpMessageFactory();
        return $factory->fromMessage($message);
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