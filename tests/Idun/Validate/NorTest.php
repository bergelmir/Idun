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
 * Test class for Idun_Validate_Nor
 */
class Idun_Validate_NorTest extends PHPUnit_Framework_TestCase
{
    /**
     * test isValid() with valid value
     */
    public function testIsValid()
    {
        $validator = new Idun_Validate_Nor(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $this->assertTrue($validator->isValid('invalid'));
    }
    
    /**
     * test isValid() with invalid value
     */
    public function testIsNotValid()
    {
        $validator = new Idun_Validate_Nor(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $this->assertFalse($validator->isValid(12345));
        
        $this->assertArrayHasKey(
            Idun_Validate_Nor::ONE_OR_MORE_CONDITIONS_TRUE,
            $validator->getMessages()
        );
        
        $this->assertFalse($validator->isValid(12.45));
        
        $this->assertArrayHasKey(
            Idun_Validate_Nor::ONE_OR_MORE_CONDITIONS_TRUE,
            $validator->getMessages()
        );
    }
}
