<?php

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
