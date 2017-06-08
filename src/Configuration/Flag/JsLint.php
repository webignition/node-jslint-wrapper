<?php

namespace webignition\NodeJslint\Wrapper\Configuration\Flag;

/**
 * Flag names accepted by jslint v2013-08-26
 */
class JsLint
{
    const ANON = 'anon';
    const BITWISE = 'bitwise';
    const BROWSER = 'browser';
    const CAP = 'cap';
    const FLAG_CONTINUE = 'continue';
    const CSS = 'css';
    const DEBUG = 'debug';
    const DEVEL = 'devel';
    const EQEQ = 'eqeq';
    const ES5 = 'es5';
    const EVIL = 'evil';
    const FORIN = 'forin';
    const FRAGMENT = 'fragment';
    const NEWCAP = 'newcap';
    const NODE = 'node';
    const NOMEN = 'nomen';
    const ON = 'on';
    const PASSFAIL = 'passfail';
    const PLUSPLUS = 'plusplus';
    const PROPERTIES = 'properties';
    const REGEXP = 'regexp';
    const RHINO = 'rhino';
    const UNDEF = 'undef';
    const UNPARAM = 'unparam';
    const SLOPPY = 'sloppy';
    const STUPID = 'stupid';
    const SUB = 'sub';
    const VARS = 'vars';
    const WHITE = 'white';
    const WIDGET = 'widget';
    const WINDOWS = 'windows';

    /**
     * @return string[]
     */
    public static function getList()
    {
        return array(
            self::ANON,
            self::BITWISE,
            self::BROWSER,
            self::CAP,
            self::FLAG_CONTINUE,
            self::CSS,
            self::DEBUG,
            self::DEVEL,
            self::EQEQ,
            self::ES5,
            self::EVIL,
            self::FORIN,
            self::FRAGMENT,
            self::NEWCAP,
            self::NODE,
            self::NOMEN,
            self::ON,
            self::PASSFAIL,
            self::PLUSPLUS,
            self::PROPERTIES,
            self::REGEXP,
            self::RHINO,
            self::UNDEF,
            self::UNPARAM,
            self::SLOPPY,
            self::STUPID,
            self::SUB,
            self::VARS,
            self::WHITE,
            self::WIDGET,
            self::WINDOWS,
        );
    }
}
