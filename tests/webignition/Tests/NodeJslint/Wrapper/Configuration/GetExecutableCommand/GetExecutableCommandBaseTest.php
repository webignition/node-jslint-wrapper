<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

abstract class GetExecutableCommandBaseTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';
    const EXPECTED_PATH_TO_LINT = '/home/example/script.js';  
    
    
    /**
     * 
     * @return string
     */
    protected function getExpectedExecutableCommandPrefix() {
        return Configuration::DEFAULT_NODE_PATH . ' ' . Configuration::DEFAULT_NODE_JSLINT_PATH;
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getExpectedFlaglessExecutableCommandPrefix() {
        return $this->getExpectedExecutableCommandPrefix() . ' --json';
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getExpectedExecutableCommandSuffix() {
        return $this->getExpectedPathToLint() . ' 2>&1';
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getExpectedPathToLint() {
        return self::EXPECTED_PATH_TO_LINT;
    }
    
}