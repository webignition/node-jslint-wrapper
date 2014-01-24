<?php

namespace webignition\Tests\NodeJslint\Wrapper\Configuration\GetExecutableCommand;

use webignition\Tests\NodeJslint\Wrapper\BaseTest;
use webignition\NodeJslint\Wrapper\Configuration\Configuration;

class FileUrlTest extends BaseTest {
    
    const URL_TO_LINT = 'file:/home/example/script.js';
    const EXPECTED_PATH_TO_LINT = '/home/example/script.js';  
    
    public function testDefaultExecutableCommand() {
        $configuration = new Configuration();
        $configuration->setUrlToLint(self::URL_TO_LINT);
        
        $this->assertEquals(
            '/usr/bin/node /usr/share/node-jslint/node_modules/jslint/bin/jslint.js --json ' . self::EXPECTED_PATH_TO_LINT . ' 2>&1',
            $configuration->getExecutableCommand()    
        );
    }    
}