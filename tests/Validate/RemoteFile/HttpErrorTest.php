<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile;

use \webignition\WebResource\Exception\Exception as WebResourceException;

class HttpErrorTest extends BaseRemoteFileTest
{
    const URL_TO_LINT = 'http://example.com/example.js';

    /**
     * @var \webignition\NodeJslint\Wrapper\Wrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = $this->getNewWrapper();
        $this->wrapper->getConfiguration()->setUrlToLint(self::URL_TO_LINT);

        $this->setHttpFixtures([
            'HTTP/1.0 ' . $this->getStatusCode()
        ]);

        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());

        try {
            $this->wrapper->validate();
            $this->fail('HTTP '.$this->getStatusCode().' exception not thrown');
        } catch (WebResourceException $webResourceException) {
            $this->assertEquals($this->getStatusCode(), $webResourceException->getResponse()->getStatusCode());
        }
    }

    public function test404()
    {
    }

    public function test500()
    {
    }

    /**
     * @return int
     */
    private function getStatusCode()
    {
        return (int)str_replace('test', '', $this->getName());
    }
}
