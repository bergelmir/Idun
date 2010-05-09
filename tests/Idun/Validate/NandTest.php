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

/**
 * Test helper
 */
require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

/**
 * Test class for Idun_Validate_Nand
 */
class Idun_Validate_NandTest extends PHPUnit_Framework_TestCase
{
    /**
     * test isValid() with valid value
     */
    public function testIsValid()
    {
        $validator = new Idun_Validate_Nand(array(
            new Zend_Validate_StringLength(7, 32),
            new Zend_Validate_EmailAddress
        ));
        file_put_contents('/tmp/debug.log', var_export(get_class_methods($validator), true));
        file_put_contents('/tmp/debug.log', var_export(method_exists($validator, 'isValid'), true), FILE_APPEND);
        $ref = new ReflectionClass($validator);
        file_put_contents('/tmp/debug.log', var_export($ref->getMethods(), true), FILE_APPEND);
        file_put_contents('/tmp/debug.log', var_export($ref->hasMethod('isValid'), true), FILE_APPEND);
        $this->assertTrue($validator->isValid('dummy'));
    }
    
    /**
     * test isValid() with invalid value
     */
    public function testIsNotValid()
    {
        $validator = new Idun_Validate_Nand(array(
            new Zend_Validate_StringLength(7, 32),
            new Zend_Validate_EmailAddress
        ));
        
        $this->assertFalse($validator->isValid('dummy@example.org'));
        
        $this->assertArrayHasKey(
            Idun_Validate_Nand::ALL_CONDITIONS_TRUE,
            $validator->getMessages()
        );
    }
}
