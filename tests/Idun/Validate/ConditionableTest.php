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
 * Test class for Idun_Validate_And
 */
class Idun_Validate_ConditionableTest extends PHPUnit_Framework_TestCase
{
    /**
     * test __construct() with only one param
     */
    public function testConstructWithOnlyOneParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(      
            $intValidator = new Zend_Validate_Int
        );
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(1, count($conditionals));
        $this->assertSame($intValidator, $conditionals[0]);
    }
    
    /**
     * test __construct() with more than one param
     */
    public function testConstructWithMoreThanOneParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(
            $intValidator = new Zend_Validate_Int,
            $floatValidator = new Zend_Validate_Float
        );
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        $this->assertSame($intValidator, $conditionals[0]);
        $this->assertSame($floatValidator, $conditionals[1]);
    }
    
    /**
     * test __construct with array param
     */
    public function testConstructWithArrayParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(array(
            $intValidator = new Zend_Validate_Int,
            $floatValidator = new Zend_Validate_Float
        ));
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        $this->assertSame($intValidator, $conditionals[0]);
        $this->assertSame($floatValidator, $conditionals[1]);
    }
    
    /**
     * test __construct with invalid params
     */
    public function testConstructWithInvalidParams()
    {
        try {
            new Idun_Validate_Conditionable_Concrete('invalid');
            $this->fail('Expected Idun_Validate_Exception has not been thrown');
        } catch (Zend_Loader_PluginLoader_Exception $exception) {
            $this->assertContains(
                'Plugin by name \'Invalid\' was not found in the registry',
                $exception->getMessage()
            );
        }
        
        try {
            new Idun_Validate_Conditionable_Concrete('NotValid');
        } catch (Idun_Validate_Exception $exception) {
            $this->assertEquals(
                'Validator class "Zend_Validate_NotValid" must implement Zend_Validate_Interface.',
                $exception->getMessage()
            );
        }
        
        try {
            new Idun_Validate_Conditionable_Concrete(new Zend_Validate_NotValid);
        } catch (Idun_Validate_Exception $exception) {
            $this->assertEquals(
                'Invalid conditional given. Must be an instance of Zend_Validate_Interface.',
                $exception->getMessage()
            );
        }
    }
    
    /**
     * test addConditional() with string param
     */
    public function testAddConditionalWithStringParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete;
        
        $validator->addConditional('Int');
        $validator->addConditional('Float');
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        $this->assertThat($conditionals[0], $this->isInstanceOf('Zend_Validate_Int'));
        $this->assertThat($conditionals[1], $this->isInstanceOf('Zend_Validate_Float'));
    }
    
    /**
     * test addConditional() with array param
     */
    public function testAddConditionalWithArrayParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete;
        
        $validator->addConditional(array(
            'validator' => 'Int',
            'options'   => array('locale' => 'en_US')
        ));
        
        $validator->addConditional(array(
            'validator' => 'StringLength',
            'options'   => array('min' => 5, 'max' => 10)
        ));
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        
        $this->assertThat($conditionals[0], $this->isInstanceOf('Zend_Validate_Int'));
        $this->assertEquals('en_US', $conditionals[0]->getLocale());
        
        $this->assertThat($conditionals[1], $this->isInstanceOf('Zend_Validate_StringLength'));
        $this->assertEquals(5, $conditionals[1]->getMin());
        $this->assertEquals(10, $conditionals[1]->getMax());
    }
    
    /**
     * test addConditional() with validator param
     */
    public function testAddConditionalWithValidatorParam()
    {
        $validator = new Idun_Validate_Conditionable_Concrete;
        
        $validator->addConditional($intValidator = new Zend_Validate_Int);
        $validator->addConditional($floatValidator = new Zend_Validate_Float);
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        $this->assertSame($intValidator, $conditionals[0]);
        $this->assertSame($floatValidator, $conditionals[1]);
    }
    
    /**
     * test addConditionals()
     */
    public function addConditionals()
    {
        $validator = new Idun_Validate_Conditionable_Concrete;
        
        $validator->addConditionals(array(
            $intValidator = new Zend_Validate_Int,
            $floatValidator = new Zend_Validate_Float
        ));
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(2, count($conditionals));
        $this->assertSame($intValidator, $conditionals[0]);
        $this->assertSame($floatValidator, $conditionals[1]);
    }
    
    /**
     * test setConditionals()
     */
    public function testSetConditionals()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $validator->setConditionals(array(
            $ipValidator = new Zend_Validate_Ip,
            $hexValidator = new Zend_Validate_Hex,
            $dateValidator = new Zend_Validate_Date
        ));
        
        $conditionals = $validator->getConditionals();
        
        $this->assertEquals(3, count($conditionals));
        $this->assertSame($ipValidator, $conditionals[0]);
        $this->assertSame($hexValidator, $conditionals[1]);
        $this->assertSame($dateValidator, $conditionals[2]);
    }
    
    /**
     * test getConditionals()
     */
    public function testGetConditionals()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $conditionals = $validator->getConditionals();
        
        $this->assertType('array', $conditionals);
        $this->assertEquals(2, count($conditionals));
    }
    
    /**
     * test clearConditionals()
     */
    public function testClearConditionals()
    {
        $validator = new Idun_Validate_Conditionable_Concrete(array(
            new Zend_Validate_Int,
            new Zend_Validate_Float
        ));
        
        $this->assertEquals(2, count($validator->getConditionals()));
        $validator->clearConditionals();
        $this->assertEquals(0, count($validator->getConditionals()));
    }
}

class Idun_Validate_Conditionable_Concrete extends Idun_Validate_Conditionable {
    public function isValid($value) {
        
    }    
}

class Zend_Validate_NotValid {}
