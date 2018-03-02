<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Message\Request;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\Configuration\Flag\JsLint;
use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy;
use webignition\NodeJslint\Wrapper\Wrapper;
use webignition\NodeJslintOutput\NodeJslintOutput;
use webignition\NodeJslintOutput\Exception as NodeJslintOutputException;
use webignition\WebResource\Exception\Exception as WebResourceException;

class WrapperTest extends BaseTest
{
    const FILE_URL_TO_LINT = 'file:/home/example/script.js';
    const REMOTE_URL_TO_LINT = 'http://example.com/example.js';
    const INVALID_REMOTE_URL_TO_LINT = 'foo://example.com/example.js';

    /**
     * @var Wrapper
     */
    private $wrapper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->wrapper = new Wrapper();
    }

    public function testCreateConfiguration()
    {
        $configurationValues = [
            Configuration::CONFIG_KEY_FLAGS => [
                JsLint::ANON => true,
            ],
        ];

        $this->wrapper->createConfiguration($configurationValues);

        $this->assertEquals(
            [
                JsLint::ANON => true,
            ],
            $this->wrapper->getConfiguration()->getFlags()
        );
    }

    public function testGetLocalProxy()
    {
        $this->assertInstanceOf(LocalProxy::class, $this->wrapper->getLocalProxy());
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateLocalFileIncorrectNodeJslintPath()
    {
        $this->wrapper->getConfiguration()->setUrlToLint(self::FILE_URL_TO_LINT);

        $this->setValidatorRawOutput(
            $this->getFixture('IncorrectNodeJsPathOutput')
        );

        $this->setExpectedException(
            NodeJslintOutputException::class,
            'node-jslint not found at "/home/example/node_modules/jslint/bin/jslint.js"'
        );

        $this->wrapper->validate();
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateLocalFileLocalFileNotFound()
    {
        $this->wrapper->getConfiguration()->setUrlToLint(self::FILE_URL_TO_LINT);

        $this->setValidatorRawOutput(
            $this->getFixture('LocalFileNotFoundOutput')
        );

        $this->setExpectedException(
            NodeJslintOutputException::class,
            'Input file "/home/example/script.js" not found'
        );

        $this->wrapper->validate();
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateLocalFileSuccess()
    {
        $this->wrapper->getConfiguration()->setUrlToLint(self::FILE_URL_TO_LINT);

        $this->setValidatorRawOutput(
            $this->getFixture('ErrorFreeOutput')
        );

        $output = $this->wrapper->validate();

        $this->assertInstanceOf(NodeJslintOutput::class, $output);
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateRemoteFileInvalidUrl()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'Url "'.self::INVALID_REMOTE_URL_TO_LINT.'" is not valid',
            1
        );

        $this->wrapper->getConfiguration()->setUrlToLint(self::INVALID_REMOTE_URL_TO_LINT);
        $this->wrapper->validate();
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     */
    public function testValidateRemoteFileHttpError()
    {
        $responseStatusCode = '404';

        $this->wrapper->getConfiguration()->setUrlToLint(self::REMOTE_URL_TO_LINT);

        $this->setHttpFixtures([
            'HTTP/1.1 ' . $responseStatusCode,
        ]);

        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());

        try {
            $this->wrapper->validate();
            $this->fail('HTTP ' . $responseStatusCode . ' exception not thrown');
        } catch (WebResourceException $webResourceException) {
            $this->assertEquals($responseStatusCode, $webResourceException->getResponse()->getStatusCode());
        }
    }

    /**
     * @throws NodeJslintOutputException
     * @throws WebResourceException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateRemoteFileCurlError()
    {
        $curlCode = '404';

        $this->wrapper->getConfiguration()->setUrlToLint(self::REMOTE_URL_TO_LINT);

        $this->setHttpFixtures([
            new ConnectException(
                'cURL error ' . $curlCode . ': message',
                new Request('GET', 'http://example.com/')
            )
        ]);

        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());

        try {
            $this->wrapper->validate();
            $this->fail('CURL ' . $curlCode . ' exception not thrown');
        } catch (ConnectException $connectException) {
            $this->assertEquals('cURL error ' . $curlCode . ': message', $connectException->getMessage());
        }
    }

    /**
     * @throws NodeJslintOutputException
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function testValidateRemoteFileSuccess()
    {
        $this->setValidatorRawOutput($this->getFixture('LocalProxyErrorFreeOutput'));

        $this->setHttpFixtures([
            "HTTP/1.0 200 OK\nContent-type: application/javascript;\n\nvar x = 1;"
        ]);

        $this->wrapper->getConfiguration()->setUrlToLint(self::REMOTE_URL_TO_LINT);
        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());
        $output = $this->wrapper->validate();

        $this->assertEquals(self::REMOTE_URL_TO_LINT, $output->getStatusLine());
    }
}
