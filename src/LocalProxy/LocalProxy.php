<?php

namespace webignition\NodeJslint\Wrapper\LocalProxy;

use webignition\WebResource\Exception\InvalidContentTypeException;
use webignition\WebResource\Service\Configuration as WebResourceServiceConfiguration;
use webignition\WebResource\Service\Service as WebResourceService;
use webignition\WebResource\WebResource;
use webignition\WebResource\Exception as WebResourceException;

/**
 * node-jslint can only be run against local files (such as /home/example/script.js)
 * This LocalProxy handles the retrieval and local-storage of remote resources
 * to make the linting of remote resources transparent at the wrapper level
 */
class LocalProxy
{
    /**
     * @var WebResourceService
     */
    private $webResourceService;

    /**
     * @var string[]
     */
    private $localRemoteResourcePaths = array();

    /**
     * @var Configuration
     */
    private $configuration = null;

    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    /**
     * @return WebResourceService
     */
    public function getWebResourceService()
    {
        if (is_null($this->webResourceService)) {
            $this->webResourceService = new WebResourceService();
            $this->webResourceService->setConfiguration(new WebResourceServiceConfiguration([
                WebResourceServiceConfiguration::CONFIG_KEY_HTTP_CLIENT => $this->configuration->getHttpClient(),
                WebResourceServiceConfiguration::CONFIG_ALLOW_UNKNOWN_RESOURCE_TYPES => false,
                WebResourceServiceConfiguration::CONFIG_KEY_CONTENT_TYPE_WEB_RESOURCE_MAP => [
                    'text/javascript' => 'webignition\WebResource\WebResource',
                    'application/javascript' => 'webignition\WebResource\WebResource',
                    'application/x-javascript' => 'webignition\WebResource\WebResource'
                ]
            ]));
        }

        return $this->webResourceService;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return WebResource
     *
     * @throws InvalidContentTypeException
     * @throws WebResourceException\Exception
     */
    private function retrieveRemoteResource()
    {
        return $this->getWebResourceService()->get($this->configuration->getHttpClient()->createRequest(
            'GET',
            $this->configuration->getUrlToLint()
        ));
    }

    /**
     * @return string
     *
     * @throws InvalidContentTypeException
     * @throws WebResourceException
     * @throws WebResourceException\Exception
     */
    public function getLocalRemoteResourcePath()
    {
        if (!$this->configuration->hasUrlToLint()) {
            throw new \RuntimeException('Url to lint has not been set', 1);
        }

        $urlToLintHash = md5($this->configuration->getUrlToLint());

        if (!isset($this->localRemoteResourcePaths[$urlToLintHash])) {
            $this->localRemoteResourcePaths[$urlToLintHash] =
                sys_get_temp_dir()
                . '/'
                . $urlToLintHash
                . '.'
                . $this->getLocalRemoteResourcePathTimestamp()
                . '.js';

            $resource = $this->retrieveRemoteResource();
            file_put_contents(
                $this->localRemoteResourcePaths[$urlToLintHash],
                $resource->getHttpResponse()->getBody()
            );
        }

        return $this->localRemoteResourcePaths[$urlToLintHash];
    }

    /**
     * @return string
     */
    protected function getLocalRemoteResourcePathTimestamp()
    {
        return (string)microtime(true);
    }

    public function clearLocalRemoteResource()
    {
        foreach ($this->localRemoteResourcePaths as $localRemoteResourcePath) {
            @unlink($localRemoteResourcePath);
        }

        $this->localRemoteResourcePaths = [];
    }
}
