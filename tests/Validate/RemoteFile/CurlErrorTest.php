<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Message\Request;

class CurlErrorTest extends BaseRemoteFileTest
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
            new ConnectException(
                'cURL error ' . $this->getStatusCode(). ': message',
                new Request('GET', 'http://example.com/')
            )
        ]);

        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());

        try {
            $this->wrapper->validate();
            $this->fail('CURL '.$this->getStatusCode().' exception not thrown');
        } catch (ConnectException $connectException) {
            $this->assertEquals('cURL error ' . $this->getStatusCode() . ': message', $connectException->getMessage());
        }
    }

    public function test6()
    {
    }

    public function test28()
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
