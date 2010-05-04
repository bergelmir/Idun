<?php

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

/**
 * Test class for Idun_Validate_Or
 */
class Idun_Validate_OrTest extends PHPUnit_Framework_TestCase
{
    /**
     * test isValid() with valid value
     */
    public function testIsValid()
    {
        $validator = new Idun_Validate_Or(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $this->assertTrue($validator->isValid(12345));
        $this->assertTrue($validator->isValid(12.45));
    }
    
    /**
     * test isValid() with invalid value
     */
    public function testIsNotValid()
    {
        $validator = new Idun_Validate_Or(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $this->assertFalse($validator->isValid('invalid'));
        $messages = $validator->getMessages();
        
        $this->assertArrayHasKey(
            Zend_Validate_Int::NOT_INT,
            $messages
        );
        
        $this->assertArrayHasKey(
            Zend_Validate_Float::NOT_FLOAT,
            $messages
        );
    }
}
