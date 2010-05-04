<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Idun_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../TestHelper.php');

class Idun_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Idun - Idun');
        
        require_once 'Idun/ArrayTest.php';
        $suite->addTestSuite('Idun_ArrayTest');
        
        require_once 'Idun/Controller/AllTests.php';
        $suite->addTest(Idun_Controller_AllTests::suite());
        
        require_once 'Idun/Form/AllTests.php';
        $suite->addTest(Idun_Form_AllTests::suite());
        
        require_once 'Idun/Validate/AllTests.php';
        $suite->addTest(Idun_Validate_AllTests::suite());
        
        require_once 'Idun/ValidateTest.php';
        $suite->addTestSuite('Idun_ValidateTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Idun_AllTests::main') {
    Idun_AllTests::main();
}
