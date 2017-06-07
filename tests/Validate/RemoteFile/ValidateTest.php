<?php

namespace webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile;

use webignition\Tests\NodeJslint\Wrapper\Validate\RemoteFile\BaseRemoteFileTest;

class ValidateTest extends BaseRemoteFileTest {

    const URL_TO_LINT = 'http://example.com/example.js';
    const INVALID_URL = 'foo://example.com/example.js';

    /**
     *
     * @var \webignition\NodeJslint\Wrapper\Wrapper
     */
    private $wrapper;

    public function setUp() {
        $this->wrapper = $this->getNewWrapper();
        $this->wrapper->getConfiguration()->setUrlToLint(self::URL_TO_LINT);
    }


    public function testInvalidUrl() {
        $this->setExpectedException('InvalidArgumentException', 'Url "'.self::INVALID_URL.'" is not valid', 1);

        $this->wrapper->getConfiguration()->setUrlToLint(self::INVALID_URL);
        $this->wrapper->validate();
    }

    public function testSuccessfulRetrieval() {
        $this->setValidatorRawOutput($this->getFixture('LocalProxyErrorFreeOutput.txt'));

        $this->setTestFixturePath(__CLASS__, __FUNCTION__);
        $this->setHttpFixtures($this->getHttpFixtures($this->getTestFixturePath() . '/HttpResponses'));

        $this->wrapper->getLocalProxy()->getConfiguration()->setHttpClient($this->getHttpClient());
        $output = $this->wrapper->validate();

        $this->assertEquals(self::URL_TO_LINT, $output->getStatusLine());
    }


}