<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Idun_Validate_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

class Idun_Validate_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Main - Idun_Validate');
        
        require_once 'Idun/Validate/ConditionableTest.php';
        $suite->addTestSuite('Idun_Validate_ConditionableTest');
        
        require_once 'Idun/Validate/AndTest.php';
        $suite->addTestSuite('Idun_Validate_AndTest');
        
        require_once 'Idun/Validate/NandTest.php';
        $suite->addTestSuite('Idun_Validate_NandTest');
        
        require_once 'Idun/Validate/NorTest.php';
        $suite->addTestSuite('Idun_Validate_NorTest');
        
        require_once 'Idun/Validate/OrTest.php';
        $suite->addTestSuite('Idun_Validate_OrTest');
        
        require_once 'Idun/Validate/XorTest.php';
        $suite->addTestSuite('Idun_Validate_XorTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Idun_Validate_AllTests::main') {
    Idun_Validate_AllTests::main();
}
