<?php

require_once realpath(dirname(__FILE__) . '/../../TestHelper.php');

/**
 * Test class for Idun_Validate_Xor
 */
class Idun_Validate_XorTest extends PHPUnit_Framework_TestCase
{
    /**
     * test isValid() with valid value
     */
    public function testIsValid()
    {
        $validator = new Idun_Validate_Xor(array(
            new Zend_Validate_Int,
            new Zend_Validate_EmailAddress
        ));
        
        $this->assertTrue($validator->isValid(1));
        $this->assertTrue($validator->isValid('dummy@example.org'));
    }
    
    /**
     * test isValid() with invalid value
     */
    public function testIsNotValid()
    {
        $validator = new Idun_Validate_Xor(array(
            new Zend_Validate_StringLength(6, 32),
            new Zend_Validate_EmailAddress
        ));
        
        $this->assertFalse($validator->isValid('dummy@example.org'));
        $messages = $validator->getMessages();
        
        $this->assertArrayHasKey(
            Idun_Validate_Xor::MATCHES_ALL_CONDITIONS,
            $messages
        );
    }
}
