<?php

/**
 * Idun
 * 
 * LICENSE
 * 
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@flabben.net so we
 * can send you a copy immediately.
 * 
 * @category   Idun
 * @package    Idun_Validate
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version    $Id:$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Idun_Validate_AllTests::main');
}

/**
 * Test helper
 */
require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

/**
 * Test class for all Idun_Validate classes
 */
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
