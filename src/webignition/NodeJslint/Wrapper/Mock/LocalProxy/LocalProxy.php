<?php
namespace webignition\NodeJslint\Wrapper\Mock\LocalProxy;

use webignition\NodeJslint\Wrapper\LocalProxy\LocalProxy as BaseLocalProxy;

/**
 * A mock of LocalProxy to ensure that the hash of a local file is constant
 * over time for reliable testing
 */
class LocalProxy extends BaseLocalProxy { 
    

    /**
     * 
     * @return string
     */
    protected function getLocalRemoteResourcePathTimestamp() {
        return 'fixed-timestamp';
    } 
    
}
