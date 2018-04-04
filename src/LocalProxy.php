<?php

namespace webignition\NodeJslint\Wrapper;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use webignition\InternetMediaType\Parser\ParseException as InternetMediaTypeParseException;
use webignition\WebResource\Exception\HttpException;
use webignition\WebResource\Exception\InvalidResponseContentTypeException;
use webignition\WebResource\Exception\TransportException;
use webignition\WebResource\Retriever as WebResourceRetriever;
use webignition\WebResource\Storage as WebResourceStorage;

/**
 * node-jslint can only be run against local files (such as /home/example/script.js)
 * This LocalProxy handles the retrieval and local-storage of remote resources
 * to make the linting of remote resources transparent at the wrapper level
 */
class LocalProxy
{
    /**
     * @var WebResourceRetriever
     */
    private $webResourceRetriever;

    /**
     * @var WebResourceStorage
     */
    private $webResourceStorage;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
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
     * @param string $urlToLint
     * @return string
     *
     * @throws HttpException
     * @throws InternetMediaTypeParseException
     * @throws InvalidResponseContentTypeException
     * @throws TransportException
     */
    public function prepare($urlToLint)
    {
        $request = new Request('GET', $urlToLint);
        $webResource = $this->webResourceRetriever->retrieve($request);

        return $this->webResourceStorage->store($webResource);
    }

    public function reset()
    {
        $this->webResourceStorage->reset();
    }
}
