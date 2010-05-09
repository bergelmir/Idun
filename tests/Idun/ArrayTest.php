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
 * @package    Idun_Array
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version    $Id:$
 */

/**
 * Test helper
 */
require_once realpath(dirname(__FILE__) . '/../TestHelper.php');

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
