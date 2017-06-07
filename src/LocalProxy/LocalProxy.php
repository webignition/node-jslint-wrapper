<?php

namespace webignition\NodeJslint\Wrapper\LocalProxy;

use webignition\WebResource\Service\Configuration as WebResourceServiceConfiguration;
use webignition\WebResource\Service\Service as WebResourceService;

/**
 * node-jslint can only be run against local files (such as /home/example/script.js)
 * This LocalProxy handles the retrieval and local-storage of remote resources
 * to make the linting of remote resources transparent at the wrapper level
 */
class LocalProxy {


    /**
     *
     * @var WebResourceService
     */
    private $webResourceService;


    /**
     *
     * @var string[]
     */
    private $localRemoteResourcePaths = array();


    /**
     *
     * @var Configuration
     */
    private $configuration = null;


    /**
     *
     * @return WebResourceService
     */
    public function getWebResourceService() {
        if (is_null($this->webResourceService)) {
            $this->webResourceService = new WebResourceService();
            $this->webResourceService->setConfiguration(new WebResourceServiceConfiguration([
                WebResourceServiceConfiguration::CONFIG_KEY_HTTP_CLIENT => $this->getConfiguration()->getHttpClient(),
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
     *
     * @return Configuration
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }


    /**
     *
     * @return \webignition\WebResource\WebResource
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    private function retrieveRemoteResource() {
        return $this->getWebResourceService()->get($this->getConfiguration()->getHttpClient()->createRequest(
            'GET',
            $this->getConfiguration()->getUrlToLint()
        ));
    }


    /**
     *
     * @return string
     */
    public function getLocalRemoteResourcePath() {
        if (!isset($this->localRemoteResourcePaths[$this->getUrlToLintHash()])) {
            $this->localRemoteResourcePaths[$this->getUrlToLintHash()] = sys_get_temp_dir() . '/' . $this->getUrlToLintHash() . '.' . $this->getLocalRemoteResourcePathTimestamp() . '.js';

            $resource = $this->retrieveRemoteResource();
            file_put_contents($this->localRemoteResourcePaths[$this->getUrlToLintHash()], $resource->getHttpResponse()->getBody(true));
        }

        return $this->localRemoteResourcePaths[$this->getUrlToLintHash()];
    }


    /**
     *
     * @return string
     */
    protected function getLocalRemoteResourcePathTimestamp() {
        return (string)microtime(true);
    }


    /**
     * @return string
     * @throws \RuntimeException
     */
    private function getUrlToLintHash() {
        if (!$this->getConfiguration()->hasUrlToLint()) {
            throw new \RuntimeException('Url to lint has not been set', 1);
        }

        return md5($this->getConfiguration()->getUrlToLint());
    }


    public function clearLocalRemoteResource() {
        foreach ($this->localRemoteResourcePaths as $localRemoteResourcePath) {
            @unlink($localRemoteResourcePath);
        }

        $this->localRemoteResourcePaths = [];
    }


}