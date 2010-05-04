<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Idun_Form_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

class Idun_Form_AllTests
{
    /**
     * @static
     * @access public
     * @return void
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(
            self::suite()
        );
    }
    
    /**
     * @static
     * @access public
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Main - Idun_Form');
        
        require_once 'Idun/Form/Helper/TokenTest.php';
        $suite->addTestSuite('Idun_Form_Helper_TokenTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Idun_Form_AllTests::main') {
    Idun_Form_AllTests::main();
}
