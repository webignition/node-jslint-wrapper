<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class FileUrlTest extends GetExecutableCommandBaseTest { 
    
    public function testDefaultExecutableCommandContainsSchemelessLocalPath() {
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $this->assertEquals(
            $this->getExpectedFlaglessExecutableCommandPrefix() . ' ' . $this->getExpectedExecutableCommandSuffix(),
            $configuration->getExecutableCommand()    
        );
    }    
}