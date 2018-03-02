<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy\Configuration;

use webignition\NodeJslint\Wrapper\LocalProxy\Configuration;
use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy;
use webignition\Tests\NodeJslint\Wrapper\AbstractBaseTest;
use webignition\WebResource\Service\Service as WebResourceService;

class LocalProxyTestAbstract extends AbstractBaseTest
{
    /**
     * @var LocalProxy
     */
    private $localProxy;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localProxy = new LocalProxy();
    }

    public function testGetWebResourceService()
    {
        $this->assertInstanceOf(WebResourceService::class, $this->localProxy->getWebResourceService());
    }

    public function testGetConfiguration()
    {
        $this->assertInstanceOf(Configuration::class, $this->localProxy->getConfiguration());
    }

    /**
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testGetLocalRemoteResourcePathUrlToLintNotSet()
    {
        $this->setExpectedException(
            \RuntimeException::class,
            'Url to lint has not been set',
            1
        );

        $this->localProxy->getLocalRemoteResourcePath();
    }

    /**
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testGetLocalRemoteResourcePath()
    {
        $this->setHttpFixtures([
            "HTTP/1.0 200 OK\nContent-Type:application/javascript",
            "HTTP/1.0 200 OK\nContent-Type:application/javascript",
        ]);

        $this->localProxy->getConfiguration()->setHttpClient($this->httpClient);
        $this->localProxy->getConfiguration()->setUrlToLint('http://example.com/one.js');

        $localPathOne = $this->localProxy->getLocalRemoteResourcePath();

        $this->localProxy->getConfiguration()->setUrlToLint('http://example.com/two.js');
        $localPathTwo = $this->localProxy->getLocalRemoteResourcePath();

        $this->assertFalse($localPathOne === $localPathTwo);
    }

    /**
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testClearLocalRemoteResource()
    {
        $this->setHttpFixtures([
            "HTTP/1.0 200 OK\nContent-Type:application/javascript",
        ]);

        $this->localProxy->getConfiguration()->setHttpClient($this->httpClient);
        $this->localProxy->getConfiguration()->setUrlToLint('http://example.com/one.js');

        $localPath = $this->localProxy->getLocalRemoteResourcePath();
        $this->assertFileExists($localPath);

        $this->localProxy->clearLocalRemoteResource();

        $this->assertFileNotExists($localPath);
    }
}
