<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class HttpUrlTest extends GetExecutableCommandBaseTest { 
    
    const URL_TO_LINT = 'http://example.com/script.js';
    const EXPECTED_PATH_TO_LINT = 'http://example.com/script.js'; 
    
    public function testDefaultExecutableCommandContainsRemoteUrl() {
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $this->assertEquals(
            $this->getExpectedFlaglessExecutableCommandPrefix() . ' ' . $this->getExpectedExecutableCommandSuffix(),
            $configuration->getExecutableCommand()    
        );
    } 
    
    
    /**
     * 
     * @return string
     */
    protected function getExpectedPathToLint() {
        return self::EXPECTED_PATH_TO_LINT;
    }    
}