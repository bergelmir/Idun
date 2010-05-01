<?php

require_once realpath(dirname(__FILE__) . '/../TestConfiguration.php');
require_once 'PHPUnit/Framework.php';

/**
 * Test class for Idun_Array
 */
class Idun_ArrayTest extends PHPUnit_Framework_TestCase
{
    /**
     * test arrayMergeRecursive()
     */
    public function testMergeRecursive()
    {
        $this->assertEquals(
            array(),
            Idun_Array::mergeRecursive(
                array(),
                array()
            )
        );
        
        $this->assertEquals(
            array('foo' => 'bar'),
            Idun_Array::mergeRecursive(
                array(),
                array('foo' => 'bar')
            )
        );
        
        $this->assertEquals(
            array('foo' => 'bar'),
            Idun_Array::mergeRecursive(
                array('foo' => 'bar'),
                array()
            )
        );
        
        $this->assertEquals(
            array('foo' => array('key' => 'bar'), 'key' => 'value'),
            Idun_Array::mergeRecursive(
                array('foo' => array(), 'key' => 'value'),
                array('foo' => array('key' => 'bar'))
            )
        );
        
        $this->assertEquals(
            array('key' => array('sub' => 'way')),
            Idun_Array::mergeRecursive(
                array('key' => 'value'),
                array('key' => array('sub' => 'way'))
            )
        );
        
        $this->assertEquals(
            array('key' => 'value'),
            Idun_Array::mergeRecursive(
                array('key' => array('sub' => 'way')),
                array('key' => 'value')
            )
        );
    }
}
