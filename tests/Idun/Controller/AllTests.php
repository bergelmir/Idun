<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Idun_Controller_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

class Idun_Controller_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Main - Idun_Controller');
        
        require_once 'Idun/Controller/Plugin/FormTokenTest.php';
        $suite->addTestSuite('Idun_Controller_Plugin_FormTokenTest');
        
        require_once 'Idun/Controller/Request/HttpTest.php';
        $suite->addTestSuite('Idun_Controller_Request_HttpTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Idun_Controller_AllTests::main') {
    Idun_Controller_AllTests::main();
}
