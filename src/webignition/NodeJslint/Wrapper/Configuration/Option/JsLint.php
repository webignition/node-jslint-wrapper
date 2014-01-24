<?php
namespace webignition\NodeJslint\Wrapper\Configuration\Option;


/**
 * Option names accepted by jslint v2013-08-26
 */
class JsLint {   

    const INDENT = 'indent';
    const MAXERR = 'maxerr';
    const MAXLEN = 'maxlen';
    const PREDEF = 'predef'; 
    
    
    /**
     * 
     * @return string[]
     */
    public static function getList() {
        return array(
            self::INDENT,
            self::MAXERR,
            self::MAXLEN,
            self::PREDEF,
        );
    }    
    
}