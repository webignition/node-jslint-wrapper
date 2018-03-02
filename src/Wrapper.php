<?php
namespace webignition\NodeJslint\Wrapper;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;
use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy;
use webignition\NodeJslintOutput\NodeJslintOutput;
use webignition\NodeJslintOutput\Parser as OutputParser;

class Wrapper
{
    const INVALID_ARGUMENT_EXCEPTION_CONFIGURATION_NOT_SET = 1;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var LocalProxy
     */
    private $localProxy;

    public function __construct()
    {
        $this->configuration = new Configuration();
        $this->localProxy = new LocalProxy();
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $configurationValues
     */
    public function createConfiguration($configurationValues)
    {
        $this->setConfiguration(new Configuration($configurationValues));
    }

    /**
     * @return NodeJslintOutput
     *
     * @throws \webignition\NodeJslintOutput\Exception
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    public function validate()
    {
        $validatorOutput = shell_exec($this->getExecutableCommand());
        if (!$this->configuration->hasFileUrlToLint()) {
            $this->getLocalProxy()->clearLocalRemoteResource();
        }

        $outputParser = new OutputParser();

        $output = $outputParser->parse($validatorOutput);

        if (!$this->configuration->hasFileUrlToLint()) {
            $this->replaceLocalStatusLineWithRemoteStatusLine($output);
        }

        return $output;
    }

    /**
     * @param NodeJslintOutput $output
     */
    private function replaceLocalStatusLineWithRemoteStatusLine(NodeJslintOutput $output)
    {
        $output->setStatusLine($this->configuration->getUrlToLint());
    }

    /**
     * @return string
     *
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    private function getExecutableCommand()
    {
        if ($this->configuration->hasFileUrlToLint()) {
            return $this->configuration->getExecutableCommand();
        }

        $this->getLocalProxy()->getConfiguration()->setUrlToLint($this->configuration->getUrlToLint());

        return $this->getExecutableCommandForRemoteResource();
    }

    /**
     * @return LocalProxy
     */
    public function getLocalProxy()
    {
        return $this->localProxy;
    }

    /**
     * @return string
     *
     * @throws \webignition\WebResource\Exception
     * @throws \webignition\WebResource\Exception\Exception
     * @throws \webignition\WebResource\Exception\InvalidContentTypeException
     */
    private function getExecutableCommandForRemoteResource()
    {
        return str_replace(
            $this->configuration->getUrlToLint(),
            $this->getLocalProxy()->getLocalRemoteResourcePath(),
            $this->configuration->getExecutableCommand()
        );
    }
}
