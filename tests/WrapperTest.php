<?php

namespace webignition\Tests\NodeJslint\Wrapper;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use webignition\InternetMediaType\Parser\ParseException as InternetMediaTypeParseException;
use webignition\NodeJslint\Wrapper\Wrapper;
use webignition\NodeJslintOutput\Entry\ParserException as NodeJslintOutputEntryParserException;
use webignition\NodeJslintOutput\NodeJslintOutput;
use webignition\NodeJslintOutput\Exception as NodeJslintOutputException;
use webignition\Tests\NodeJslint\Wrapper\Factory\FixtureLoader;
use webignition\WebResource\Exception\HttpException;
use webignition\WebResource\Exception\InvalidResponseContentTypeException;
use webignition\WebResource\Exception\TransportException;

class WrapperTest extends AbstractBaseTest
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
        $this->wrapper->setHttpClient($this->httpClient);
    }

    /**
     * @dataProvider validateThrowsExceptionDataProvider
     *
     * @param string $urlToLint
     * @param array $httpFixtures
     * @param string $validatorRawOutput
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws NodeJslintOutputEntryParserException
     * @throws NodeJslintOutputException
     * @throws TransportException
     */
    public function testValidateThrowsException(
        $urlToLint,
        array $httpFixtures,
        $validatorRawOutput,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->appendHttpFixtures($httpFixtures);

        if (!is_null($validatorRawOutput)) {
            $this->setValidatorRawOutput($validatorRawOutput);
        }

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->wrapper->validate($urlToLint);
    }

    /**
     * @return array
     */
    public function validateThrowsExceptionDataProvider()
    {
        return [
            'incorrect node-jslint path' => [
                'urlToLint' => self::FILE_URL_TO_LINT,
                'httpFixtures' => [],
                'validatorRawOutput' => FixtureLoader::load('IncorrectNodeJsPathOutput.txt'),
                'expectedException' => NodeJslintOutputException::class,
                'expectedExceptionMessage' =>
                    'node-jslint not found at "/home/example/node_modules/jslint/bin/jslint.js"',
                'expectedExceptionCode' => NodeJslintOutputException::CODE_INCORRECT_NODE_JS_PATH,
            ],
            'local file not found' => [
                'urlToLint' => self::FILE_URL_TO_LINT,
                'httpFixtures' => [],
                'validatorRawOutput' => FixtureLoader::load('LocalFileNotFoundOutput.txt'),
                'expectedException' => NodeJslintOutputException::class,
                'expectedExceptionMessage' => 'Input file "/home/example/script.js" not found',
                'expectedExceptionCode' => NodeJslintOutputException::CODE_INPUT_FILE_NOT_FOUND,
            ],
            'invalid remote file url' => [
                'urlToLint' => self::INVALID_REMOTE_URL_TO_LINT,
                'httpFixtures' => [],
                'validatorRawOutput' => null,
                'expectedException' => \InvalidArgumentException::class,
                'expectedExceptionMessage' => 'Url "foo://example.com/example.js" is not valid',
                'expectedExceptionCode' => 100
            ],
            'remote file http 404' => [
                'urlToLint' => self::REMOTE_URL_TO_LINT,
                'httpFixtures' => [
                    new Response(404),
                    new Response(404),
                ],
                'validatorRawOutput' => null,
                'expectedException' => HttpException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404
            ],
            'remote file curl 28' => [
                'urlToLint' => self::REMOTE_URL_TO_LINT,
                'httpFixtures' => [
                    $curl28ConnectException = new ConnectException(
                        'cURL error 28: Operation timeout',
                        new Request('GET', self::REMOTE_URL_TO_LINT)
                    ),
                    $curl28ConnectException = new ConnectException(
                        'cURL error 28: Operation timeout',
                        new Request('GET', self::REMOTE_URL_TO_LINT)
                    ),
                ],
                'validatorRawOutput' => null,
                'expectedException' => TransportException::class,
                'expectedExceptionMessage' => 'Operation timeout',
                'expectedExceptionCode' => 28
            ],
        ];
    }

    /**
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws NodeJslintOutputEntryParserException
     * @throws NodeJslintOutputException
     * @throws TransportException
     */
    public function testValidateRemoteFileSuccess()
    {
        $this->setValidatorRawOutput(FixtureLoader::load('LocalProxyErrorFreeOutput.txt'));

        $this->appendHttpFixtures([
            new Response(200, ['content-type' => 'application/javascript']),
            new Response(200, ['content-type' => 'application/javascript'], 'var x = 1;'),
        ]);

        $output = $this->wrapper->validate(self::REMOTE_URL_TO_LINT);

        $this->assertEquals(self::REMOTE_URL_TO_LINT, $output->getStatusLine());
    }

    /**
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws NodeJslintOutputEntryParserException
     * @throws NodeJslintOutputException
     * @throws TransportException
     */
    public function testValidateLocalFileSuccess()
    {
        $this->setValidatorRawOutput(FixtureLoader::load('ErrorFreeOutput.txt'));

        $output = $this->wrapper->validate(self::FILE_URL_TO_LINT);

        $this->assertInstanceOf(NodeJslintOutput::class, $output);
    }
}
