<?php
namespace webignition\NodeJslint\Wrapper\LocalProxy;

use GuzzleHttp\Client as HttpClient;

class Configuration
{
    /**
     * @var string
     */
    private $urlToLint = null;

    /**
     * @var HttpClient
     */
    private $httpClient = null;

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient;
        }

        return $this->httpClient;
    }

    /**
     * @param $url

     * @throws \InvalidArgumentException
     */
    public function setUrlToLint($url)
    {
        if (!$this->urlHasExpectedScheme($url)) {
            throw new \InvalidArgumentException('Url "'.$url.'" is not valid', 1);
        }

        $this->urlToLint = $url;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    private function urlHasExpectedScheme($url)
    {
        $expectedSchemes = array(
            'http://',
            'https://',
        );

        foreach ($expectedSchemes as $scheme) {
            if (substr($url, 0, strlen($scheme)) == $scheme) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUrlToLint()
    {
        return $this->urlToLint;
    }

    /**
     * @return bool
     */
    public function hasUrlToLint()
    {
        return !is_null($this->urlToLint);
    }
}
