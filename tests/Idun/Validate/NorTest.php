<?php

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
