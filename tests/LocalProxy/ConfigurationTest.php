<?php

namespace webignition\Tests\NodeJslint\Wrapper\LocalProxy\Configuration;

use GuzzleHttp\Client;
use webignition\NodeJslint\Wrapper\LocalProxy\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
    }

    public function testSetGetHttpClient()
    {
        $httpClient = new Client();

        $this->assertNotEquals(spl_object_hash($httpClient), spl_object_hash($this->configuration->getHttpClient()));

        $this->configuration->setHttpClient($httpClient);

        $this->assertEquals(spl_object_hash($httpClient), spl_object_hash($this->configuration->getHttpClient()));
    }

    /**
     * @dataProvider setGetUrlToLintDataProvider
     *
     * @param string $url
     */
    public function testSetGetUrlToLint($url)
    {
        $this->configuration->setUrlToLint($url);

        $this->assertEquals($url, $this->configuration->getUrlToLint());
    }

    /**
     * @return array
     */
    public function setGetUrlToLintDataProvider()
    {
        return [
            'http url is valid' => [
                'url' => 'http://example.com/foo.js',
            ],
            'https url is valid' => [
                'url' => 'https://example.com/foo.js',
            ],
        ];
    }

    public function testSetUrlToLintInvalidScheme()
    {
        $url = 'ftp://example.com/foo.js';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Url "' . $url . '" is not valid');
        $this->expectExceptionCode(1);

        $this->configuration->setUrlToLint($url);
    }

    public function testHasUrlToLint()
    {
        $this->assertFalse($this->configuration->hasUrlToLint());

        $this->configuration->setUrlToLint('http://example.com/foo.js');

        $this->assertTrue($this->configuration->hasUrlToLint());
    }
}
