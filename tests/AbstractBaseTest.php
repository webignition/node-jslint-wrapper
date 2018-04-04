<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use phpmock\mockery\PHPMockery;
use GuzzleHttp\Client as HttpClient;

abstract class AbstractBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * @var HttpClient
     */
    protected $httpClient;
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $this->httpClient = new HttpClient(['handler' => HandlerStack::create($this->mockHandler)]);
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
     * @param array $httpFixtures
     */
    protected function appendHttpFixtures(array $httpFixtures)
    {
        foreach ($httpFixtures as $httpFixture) {
            $this->mockHandler->append($httpFixture);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function assertPostConditions()
    {
        parent::assertPostConditions();

        $this->assertEquals(0, $this->mockHandler->count());
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
