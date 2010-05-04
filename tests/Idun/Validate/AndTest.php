<?php

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

/**
 * Test class for Idun_Validate_And
 */
class Idun_Validate_AndTest extends PHPUnit_Framework_TestCase
{
    /**
     * test isValid() with valid value
     */
    public function testIsValid()
    {
        $validator = new Idun_Validate_And(array(
            new Zend_Validate_StringLength(7, 32),
            new Zend_Validate_EmailAddress
        ));
        
        $this->assertTrue($validator->isValid('dummy@example.org'));
    }
    
    /**
     * test isValid() with invalid value
     */
    public function testIsNotValid()
    {
        $validator = new Idun_Validate_And(array(
            new Zend_Validate_StringLength(7, 32),
            new Zend_Validate_EmailAddress
        ));
        
        $this->assertFalse($validator->isValid('dummy'));
        
        $this->assertArrayHasKey(
            Zend_Validate_StringLength::TOO_SHORT,
            $validator->getMessages()
        );
        
        $this->assertFalse($validator->isValid('invalid'));
        
        $this->assertArrayHasKey(
            Zend_Validate_EmailAddress::INVALID_FORMAT,
            $validator->getMessages()
        );
    }
}
