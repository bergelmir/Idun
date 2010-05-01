<?php

require_once realpath(dirname(__FILE__) . '/../../../TestConfiguration.php');
require_once 'PHPUnit/Framework.php';

/**
 * Test class for Idun_Controller_Helper_FormToken
 */
class Idun_Controller_Helper_FormTokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up test case
     */
    public function setUp()
    {
        Zend_Session::$_unitTestEnabled = true;
    }
    
    /**
     * test __construct() without params
     */
    public function testConstructWithoutParams()
    {
        $formTokenHelper = new Idun_Controller_Plugin_FormToken;
        $this->assertSame(null, $formTokenHelper->getHelper(false));
    }
    
    /**
     * test __construct() with array
     */
    public function testConstructWithArray()
    {
        $formTokenHelper = new Idun_Controller_Plugin_FormToken(array(
            'tokenKey' => 'testTokenKey'
        ));
        $this->assertEquals(
            'testTokenKey',
            $formTokenHelper->getHelper()->getTokenKey()
        );
    }
    
    /**
     * test __construct() with Zend_Config
     */
    public function testConstructWithZendConfig()
    {
        $formTokenHelper = new Idun_Controller_Plugin_FormToken(new Zend_Config(array(
            'tokenKey' => 'testTokenKey'
        )));
        $this->assertEquals(
            'testTokenKey',
            $formTokenHelper->getHelper()->getTokenKey()
        );
    }
    
    /**
     * test __construct() with Idun_Form_Helper_Token
     */
    public function testConstructWithIdunFormHelperToken()
    {
        $formTokenHelper = new Idun_Controller_Plugin_FormToken(new Idun_Form_Helper_Token(array(
            'tokenKey' => 'testTokenKey'
        )));
        $this->assertEquals(
            'testTokenKey',
            $formTokenHelper->getHelper()->getTokenKey()
        );
    }
    
    /**
     * test __construct() with invalid param
     */
    public function testConstructWithInvalidParam()
    {
        try {
            new Idun_Controller_Plugin_FormToken('string-not-allowed');
            $this->fail('Expected Idun_Controller_Plugin_Exception has not been thrown');
        } catch (Idun_Controller_Plugin_Exception $exception) {
            $this->assertContains('String given.', $exception->getMessage());
        }
    }
    
    /**
     * test setHelper() & getHelper()
     */
    public function testSetAndGetHelper()
    {
        $formTokenHelper = new Idun_Controller_Plugin_FormToken;
        $this->assertSame(null, $formTokenHelper->getHelper(false));
        
        try {
            $formTokenHelper->getHelper(true);
            $this->fail('Expected Idun_Controller_Plugin_Exception has not been thrown');
        } catch (Idun_Controller_Plugin_Exception $exception) {
            $this->assertEquals('No token helper set.', $exception->getMessage());
        }
        
        $formHelperToken = new Idun_Form_Helper_Token;
        $formTokenHelper->setHelper($formHelperToken);
        $this->identicalTo($formHelperToken, $formTokenHelper->getHelper());
    }
    
    /**
     * test dispatchLoopStartup()
     */
    public function testDispatchLoopStartup()
    {
        $formHelperToken = new Idun_Form_Helper_Token(array(
            'tokenKey' => 'testTokenKey'
        ));
        
        $formTokenHelper = new Idun_Controller_Plugin_FormToken(
            $formHelperToken
        );
        
        $request = new Zend_Controller_Request_Http;
        
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertSame(null, $formTokenHelper->dispatchLoopStartup($request));
        
        $formHelperToken->addToken('test-token');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request->setPost('testTokenKey', 'test-token');
        
        $this->assertTrue($formTokenHelper->dispatchLoopStartup($request));
        $this->assertFalse($formHelperToken->hasToken('test-token'));
        
        $formHelperToken->addToken('test-token');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request->setPost('testTokenKey', 'not-existent-token');
        
        $this->assertFalse($formTokenHelper->dispatchLoopStartup($request));
        $this->assertEquals('GET', $_SERVER['REQUEST_METHOD']);
        $this->assertSame(array(), $request->getPost());
    }
    
    /**
     * test postDispatch()
     */
    public function testPostDispatch()
    {
        $response = new Zend_Controller_Response_Http;
        $response->setBody('<form method="post" action="/"></form>');
        Zend_Controller_Front::getInstance()->setResponse($response);
        
        $formHelperToken = new Idun_Form_Helper_Token(array(
            'tokenKey' => 'testTokenKey'
        ));
        
        $formTokenHelper = new Idun_Controller_Plugin_FormToken(
            $formHelperToken
        );
        
        $formTokenHelper->postDispatch(new Zend_Controller_Request_Http);
        
        $token = current($_SESSION['Idun_Form_Helper_Token']['tokens']);
        
        $this->assertEquals(
            '<form method="post" action="/">' .
                '<div><input type="hidden" name="testTokenKey" value="' . $token . '" /></div>' .
            '</form>',
            $response->getBody()
        );
    }
}
