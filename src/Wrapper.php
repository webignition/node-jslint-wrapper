<?php

namespace webignition\NodeJslint\Wrapper;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use webignition\InternetMediaType\Parser\ParseException as InternetMediaTypeParseException;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslintOutput\Entry\ParserException as NodeLslintEntryParserException;
use webignition\NodeJslintOutput\Exception as NodeJslintException;
use webignition\NodeJslintOutput\NodeJslintOutput;
use webignition\NodeJslintOutput\Parser as OutputParser;
use webignition\WebResource\Exception\HttpException;
use webignition\WebResource\Exception\InvalidResponseContentTypeException;
use webignition\WebResource\Exception\TransportException;
use webignition\WebResource\Retriever as WebResourceRetriever;
use webignition\WebResource\Storage as WebResourceStorage;

class Wrapper
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var CommandFactory
     */
    private $commandFactory;

    /**
     * @var WebResourceRetriever
     */
    private $webResourceRetriever;

    /**
     * @var WebResourceStorage
     */
    private $webResourceStorage;

    public function __construct()
    {
        $this->setConfiguration(new Configuration());
        $httpClient = new HttpClient();

        $this->webResourceRetriever = new WebResourceRetriever(
            $httpClient,
            [
                'text/javascript',
                'application/javascript',
                'application/x-javascript',
            ],
            false
        );

        $this->webResourceStorage = new WebResourceStorage();
    }

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->webResourceRetriever->setHttpClient($httpClient);
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->commandFactory = new CommandFactory($configuration);
    }

    /**
     * @param string $urlToLint
     *
     * @return NodeJslintOutput
     *
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws TransportException
     * @throws NodeLslintEntryParserException
     * @throws NodeJslintException
     */
    public function validate($urlToLint)
    {
        if (!$this->urlHasExpectedScheme($urlToLint)) {
            throw new \InvalidArgumentException('Url "' . $urlToLint . '" is not valid', 100);
        }

        $isFileUrl = FileUrlDetector::isFileUrl($urlToLint);

        $validatorOutput = shell_exec($this->getExecutableCommand($urlToLint));
        if (!$isFileUrl) {
            $this->webResourceStorage->reset();
        }

        $outputParser = new OutputParser();

        $output = $outputParser->parse($validatorOutput);

        if (!$isFileUrl) {
            $output->setStatusLine($urlToLint);
        }

        return $output;
    }

    /**
     * @param string $url
     *
     * @return boolean
     */
    private function urlHasExpectedScheme($url)
    {
        $expectedSchemes = [
            'http:',
            'https:',
            'file:'
        ];

        foreach ($expectedSchemes as $scheme) {
            if (substr($url, 0, strlen($scheme)) == $scheme) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $urlToLint
     *
     * @return string
     *
     * @throws InternetMediaTypeParseException
     * @throws HttpException
     * @throws InvalidResponseContentTypeException
     * @throws TransportException
     */
    private function getExecutableCommand($urlToLint)
    {
        $executableCommand = $this->commandFactory->create($urlToLint);
        if (FileUrlDetector::isFileUrl($urlToLint)) {
            return $executableCommand;
        }

        $localRemoteResourcePath = $this->createLocalPathForRemoteResource($urlToLint);

        return str_replace($urlToLint, $localRemoteResourcePath, $executableCommand);
    }

    /**
     * @param string $urlToLint
     * @return string
     *
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws TransportException
     */
    public function createLocalPathForRemoteResource($urlToLint)
    {
        $request = new Request('GET', $urlToLint);
        $webResource = $this->webResourceRetriever->retrieve($request);

        return $this->webResourceStorage->store($webResource);
    }
}
