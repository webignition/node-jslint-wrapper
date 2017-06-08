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

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        if (is_null($this->configuration)) {
            $this->configuration = new Configuration();
        }

        return $this->configuration;
    }

    /**
     * @return boolean
     */
    public function hasConfiguration()
    {
        return !is_null($this->getConfiguration());
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return NodeJslintOutput
     */
    public function validate()
    {
        if (!$this->hasConfiguration()) {
            throw new \InvalidArgumentException(
                'Unable to validate; configuration not set',
                self::INVALID_ARGUMENT_EXCEPTION_CONFIGURATION_NOT_SET
            );
        }

        $validatorOutput = shell_exec($this->getExecutableCommand());
        if (!$this->getConfiguration()->hasFileUrlToLint()) {
            $this->getLocalProxy()->clearLocalRemoteResource();
        }

        $outputParser = new OutputParser();

        $output = $outputParser->parse($validatorOutput);

        if (!$this->getConfiguration()->hasFileUrlToLint()) {
            $this->replaceLocalStatusLineWithRemoteStatusLine($output);
        }

        return $output;
    }

    /**
     * @param NodeJslintOutput $output
     */
    private function replaceLocalStatusLineWithRemoteStatusLine(NodeJslintOutput $output)
    {
        $output->setStatusLine($this->getConfiguration()->getUrlToLint());
    }

    /**
     * @return string
     */
    private function getExecutableCommand()
    {
        if ($this->getConfiguration()->hasFileUrlToLint()) {
            return $this->getConfiguration()->getExecutableCommand();
        }

        $this->getLocalProxy()->getConfiguration()->setUrlToLint($this->getConfiguration()->getUrlToLint());

        return $this->getExecutableCommandForRemoteResource();
    }

    /**
     * @return LocalProxy
     */
    public function getLocalProxy()
    {
        if (is_null($this->localProxy)) {
            $this->localProxy = $this->createLocalProxy();
        }

        return $this->localProxy;
    }

    /**
     * @return LocalProxy
     */
    protected function createLocalProxy()
    {
        return new LocalProxy();
    }

    /**
     * @param LocalProxy $localProxy
     */
    protected function setLocalProxy(LocalProxy $localProxy)
    {
        $this->localProxy = $localProxy;
    }

    /**
     * @return string
     */
    private function getExecutableCommandForRemoteResource()
    {
        return str_replace(
            $this->getConfiguration()->getUrlToLint(),
            $this->getLocalProxy()->getLocalRemoteResourcePath(),
            $this->getConfiguration()->getExecutableCommand()
        );
    }
}
