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
require_once realpath(dirname(__FILE__) . '/../TestHelper.php');

/**
 * Test class for Idun_Validate
 */
class Idun_ValidateTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up test case
     */
    public function setUp()
    {
        Idun_Validate::unsetPluginLoader();
    }
    
    /**
     * test setPluginLoader() and getPluginLoader()
     */
    public function testSetAndGetPluginLoader()
    {
        Idun_Validate::setPluginLoader($pluginLoader = new Zend_Loader_PluginLoader);
        $this->assertSame($pluginLoader, Idun_Validate::getPluginLoader());
    }
    
    /**
     * test getPluginLoader() without previously set plugin loader
     */
    public function testGetPluginLoader()
    {
        $this->assertThat(
            Idun_Validate::getPluginLoader(),
            $this->isInstanceOf('Zend_Loader_PluginLoader')
        );
    }
    
    /**
     * test unsetPluginLoader()
     */
    public function testUnsetPluginLoader()
    {
        Idun_Validate::setPluginLoader(new Zend_Loader_PluginLoader);
        $this->assertTrue(Idun_Validate_Test::hasPluginLoader());
        Idun_Validate::unsetPluginLoader();
        $this->assertFalse(Idun_Validate_Test::hasPluginLoader());
    }
    
    /**
     * test getValidator() with string param
     */
    public function testGetValidatorWithStringParam()
    {
        $this->assertThat(
            Idun_Validate::getValidator('Int'),
            $this->isInstanceOf('Zend_Validate_Int')
        );
        
        $this->assertThat(
            Idun_Validate::getValidator('Zend_Validate_Int'),
            $this->isInstanceOf('Zend_Validate_Int')
        );
    }
    
    /**
     * test getValidator() with array param
     */
    public function testGetValidatorWithArrayParam()
    {
        $this->assertThat(
            $validator = Idun_Validate::getValidator(array(
                'validator' => 'StringLength',
                'options'   => array('min' => 23, 'max' => 42)
            )),
            $this->isInstanceOf('Zend_Validate_StringLength')
        );
        
        $this->assertEquals(23, $validator->getMin());
        $this->assertEquals(42, $validator->getMax());
    }
    
    /**
     * test getValidator() with invalid params
     */
    public function testGetValidatorWithInvalidParams()
    {
        try {
            Idun_Validate::getValidator(array('invalid-param'));
            $this->fail('Expected Idun_Validate_Exception has not been thrown');
        } catch (Idun_Validate_Exception $exception) {
            $this->assertEquals(
                'No validator class name given.', 
                $exception->getMessage()
            );
        }
        
        try {
            Idun_Validate::getValidator('Test_InvalidValidator');
            $this->fail('Expected Idun_Validate_Exception has not been thrown');
        } catch (Idun_Validate_Exception $exception) {
            $this->assertEquals(
                'Validator class "Test_InvalidValidator" must implement Zend_Validate_Interface.',
                $exception->getMessage()
            );
        }
    }
    
    /**
     * test getValidator() with messages
     */
    public function testGetValidatorWithMessages()
    {
        $validator = Idun_Validate::getValidator(array(
            'validator' => 'Int',
            'messages'  => array('notInt' => 'dummy notInt message')
        ));
        
        $this->assertFalse($validator->isValid('invalid'));
        
        $messages = $validator->getMessages();
        $this->assertArrayHasKey(Zend_Validate_Int::NOT_INT, $messages);
        $this->assertEquals('dummy notInt message', $messages[Zend_Validate_Int::NOT_INT]);
        
        $validator = Idun_Validate::getValidator(array(
            'validator' => 'Int',
            'options'   => array('messages' => array('notInt' => 'dummy notInt message'))
        ));
        
        $this->assertFalse($validator->isValid('invalid'));
        
        $messages = $validator->getMessages();
        $this->assertArrayHasKey(Zend_Validate_Int::NOT_INT, $messages);
        $this->assertEquals('dummy notInt message', $messages[Zend_Validate_Int::NOT_INT]);
        
        $validator = Idun_Validate::getValidator(array(
            'validator' => 'Int',
            'messages'  => 'dummy message'
        ));
        
        $this->assertFalse($validator->isValid('invalid'));
        
        $messages = $validator->getMessages();
        $this->assertArrayHasKey(Zend_Validate_Int::NOT_INT, $messages);
        $this->assertEquals('dummy message', $messages[Zend_Validate_Int::NOT_INT]);
    }
    
    /**
     * test getValidator() with multiple constructor params
     */
    public function testGetValidatorWithMultipleConstructorParams()
    {
        $validator = Idun_Validate::getValidator(array(
            'validator' => 'Zend_Validate_StringLength',
            'options'   => array(23, 42)
        ));
        
        $this->assertEquals(23, $validator->getMin());
        $this->assertEquals(42, $validator->getMax());
    }
    
    /**
     * test is()
     */
    public function testIs()
    {
        $this->assertTrue(Idun_Validate::is(
            23, 'Zend_Validate_Int'
        ));
        
        $this->assertTrue(Idun_Validate::is(
            42, 'Int'
        ));
        
        $this->assertTrue(Idun_Validate::is(
            'test', 'Zend_Validate_StringLength',
            array('min' => 1, 'max' => 5)
        ));
        
        $this->assertTrue(Idun_Validate::is(
            'dummy@example.org',
            'And',
            array(
                new Zend_Validate_StringLength(3, 32),
                new Zend_Validate_EmailAddress
            ),
            array('Idun_Validate')
        ));
    }
    
    /**
     * test is() with invalid params
     */
    public function testIsWithInvalidParams()
    {
        try {
            Idun_Validate::is(123, 'Dummy');
            $this->fail('Expected Idun_Validate_Exception has not been thrown');
        } catch (Idun_Validate_Exception $exception) {
            $this->assertEquals(
                'Validate class not found from basename \'Dummy\'',
                $exception->getMessage()
            );
        }
    }
}

class Idun_Validate_Test extends Idun_Validate {
    public static function hasPluginLoader() {
        return self::$_pluginLoader !== null;
    }
}

class Test_InvalidValidator {
    
}
