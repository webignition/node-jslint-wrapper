<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint as JsLintFlag;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock as HttpMockSubscriber;
use webignition\NodeJslint\Wrapper\Wrapper;

abstract class AbstractBaseTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURES_BASE_PATH = '/fixtures';

    /**
     * @var HttpClient
     */
    protected $httpClient = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpClient = new HttpClient();
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
     * @param array $fixtures
     */
    protected function setHttpFixtures($fixtures)
    {
        $this->httpClient->getEmitter()->attach(
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
