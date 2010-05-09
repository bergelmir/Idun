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
 * @package    Idun_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version    $Id:$
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * Test class for Idun_Controller_Request_Http
 */
class Idun_Controller_Request_HttpTest extends PHPUnit_Framework_TestCase
{
    /**
     * @access public
     * @return void
     */
    public function setUp()
    {
        $_GET['get-key'] = 'get-value';
        $_POST['post-key'] = 'post-value';
        $_SERVER['server-key'] = 'server-value';
        $_COOKIE['cookie-key'] = 'cookie-value';
        $_ENV['env-key'] = 'env-value';
        $_GET['hex-value-key'] = '0xABC123';
    }
    
    /**
     * test getQuery()
     */
    public function testGetQuery()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertEquals('get-value', $request->getQuery('get-key'));
        $this->assertEquals(null, $request->getQuery('not-existent'));
        $this->assertEquals('default', $request->getQuery('get-key', 'default', 'integer'));
    }
    
    /**
     * test getPost()
     */
    public function testGetPost()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertEquals('post-value', $request->getPost('post-key'));
        $this->assertEquals(null, $request->getPost('not-existent'));
        $this->assertEquals('default', $request->getPost('post-key', 'default', 'integer'));
    }
    
    /**
     * test getServer()
     */
    public function testGetServer()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertEquals('server-value', $request->getServer('server-key'));
        $this->assertEquals(null, $request->getServer('not-existent'));
        $this->assertEquals('default', $request->getServer('server-key', 'default', 'integer'));
    }
    
    /**
     * test getCookie()
     */
    public function testGetCookie()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertEquals('cookie-value', $request->getCookie('cookie-key'));
        $this->assertEquals(null, $request->getCookie('not-existent'));
        $this->assertEquals('default', $request->getCookie('cookie-key', 'default', 'integer'));
    }
    
    /**
     * test getEnv()
     */
    public function testGetEnv()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertEquals('env-value', $request->getEnv('env-key'));
        $this->assertEquals(null, $request->getEnv('not-existent'));
        $this->assertEquals('default', $request->getEnv('env-key', 'default', 'integer'));
    }
    
    /**
     * test unknown filter definition
     */
    public function testUnkownFilterType()
    {
        try {
            $request = new Idun_Controller_Request_Http;
            $request->getQuery('dummy', null, 'unknown-filter-type');
            $this->fail('Expected Idun_Controller_Request_Exception has not been thrown');
        } catch (Idun_Controller_Request_Exception $exception) {
            $this->assertEquals('Unknown filter type "unknown-filter-type".', $exception->getMessage());
        }
    }
    
    /**
     * test filter flags
     */
    public function testFilterFlags()
    {
        $request = new Idun_Controller_Request_Http;
        $this->assertSame(11256099, $request->getQuery(
            'hex-value-key',
            null,
            'integer',
            array('flags' => FILTER_FLAG_ALLOW_HEX)
        ));
    }
}
