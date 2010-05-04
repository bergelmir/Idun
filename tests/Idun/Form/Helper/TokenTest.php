<?php

require_once realpath(dirname(__FILE__) . '/../../../TestHelper.php');

/**
 * Test class for Idun_Form_Helper_Token.
 */
class Idun_Form_Helper_TokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @access protected
     * @var    Idun_Form_Helper_Token
     */
    protected $_formHelperToken = null;
    
    /**
     * set up test case
     */
    protected function setUp()
    {
        $this->_formHelperToken = new Idun_Form_Helper_Token;
        Zend_Session::$_unitTestEnabled = true;
    }
    
    /**
     * tests __construct()
     */
    public function testConstructor()
    {
        $formHelperToken = new Idun_Form_Helper_Token(array(
            'tokenKey'         => 'myFormTokenKey',
            'sessionNamespace' => 'MyDummySessionNamespace'
        ));
        
        $this->assertEquals('myFormTokenKey', $formHelperToken->getTokenKey());
        $this->assertEquals('MyDummySessionNamespace', $formHelperToken->getSessionNamespace());
        
        $formHelperToken = new Idun_Form_Helper_Token(new Zend_Config(array(
            'tokenKey'         => 'myFormTokenKey',
            'sessionNamespace' => 'MyDummySessionNamespace'
        )));
        
        $this->assertEquals('myFormTokenKey', $formHelperToken->getTokenKey());
        $this->assertEquals('MyDummySessionNamespace', $formHelperToken->getSessionNamespace());
    }
    
    /**
     * tests setConfig()
     */
    public function testSetConfig()
    {
        $this->_formHelperToken->setConfig(new Zend_Config(array(
            'tokenKey'         => 'myFormTokenKey',
            'sessionNamespace' => 'MyDummySessionNamespace'
        )));
        
        $this->assertEquals('myFormTokenKey', $this->_formHelperToken->getTokenKey());
        $this->assertEquals('MyDummySessionNamespace', $this->_formHelperToken->getSessionNamespace());
    }
    
    /**
     * tests setOptions()
     */
    public function testSetOptions()
    {
        $this->_formHelperToken->setOptions(array(
            'tokenKey'         => 'myFormTokenKey',
            'sessionNamespace' => 'MyDummySessionNamespace'
        ));
        
        $this->assertEquals('myFormTokenKey', $this->_formHelperToken->getTokenKey());
        $this->assertEquals('MyDummySessionNamespace', $this->_formHelperToken->getSessionNamespace());
    }
    
    /**
     * tests setOptions() with unknown option
     */
    public function testSetUnknownOption()
    {
        try {
            $this->_formHelperToken->setOptions(array('notExistent' => '1'));
            $this->fail('An expected Idun_Form_Helper_Exception has not been raised.');
        } catch (Idun_Form_Helper_Exception $exception) {
            $this->assertEquals('Unknown option "notExistent".', $exception->getMessage());
        }
    }
    
    /**
     * tests setTokenLength() and getTokenLength()
     */
    public function testGetAndSetTokenLength()
    {
        $this->assertEquals(32, $this->_formHelperToken->getTokenLength());
        $this->_formHelperToken->setTokenLength(16);
        $this->assertEquals(16, $this->_formHelperToken->getTokenLength());
    }
    
    /**
     * tests setTokenLength() with invalid length
     */
    public function testSetInvalidTokenLength()
    {
        try {
            $this->_formHelperToken->setTokenLength(1);
            $this->fail('An expected Idun_Form_Helper_Exception has not been raised.');
        } catch (Idun_Form_Helper_Exception $exception) {
            $this->assertEquals('Token length must be greater or equal 6.', $exception->getMessage());
        }
    }
    
    /**
     * tests setTokenKey() and getTokenKey()
     */
    public function testGetAndSetTokenKey()
    {
        $this->assertEquals('formToken', $this->_formHelperToken->getTokenKey());
        $this->_formHelperToken->setTokenKey('formTokenKey');
        $this->assertEquals('formTokenKey', $this->_formHelperToken->getTokenKey());
    }
    
    /**
     * tests setTokenKey() with invalid token key
     */
    public function testSetInvalidTokenKey()
    {
        try {
            $this->_formHelperToken->setTokenKey('##### Invalid Token Key #####');
            $this->fail('An expected Idun_Form_Helper_Exception has not been raised.');
        } catch (Idun_Form_Helper_Exception $exception) {
            $this->assertEquals('Token key "##### Invalid Token Key #####" should only contain alphanumeric characters and underscores.', $exception->getMessage());
        }
    }
    
    /**
     * tests setSessionNamespace() and getSessionNamespace()
     */
    public function testGetAndSetSessionNamespace()
    {
        $this->assertEquals('Idun_Form_Helper_Token', $this->_formHelperToken->getSessionNamespace());
        $this->_formHelperToken->setSessionNamespace('MySessionNamespace');
        $this->assertEquals('MySessionNamespace', $this->_formHelperToken->getSessionNamespace());
    }
    
    /**
     * tests setMaximumTokenCount() and getMaximumTokenCount()
     */
    public function testGetAndSetMaximumTokenCount()
    {
        $this->assertEquals(10, $this->_formHelperToken->getMaximumTokenCount());
        $this->_formHelperToken->setMaximumTokenCount(5);
        $this->assertEquals(5, $this->_formHelperToken->getMaximumTokenCount());
    }
    
    /**
     * tests setMaximumTokenCount() with invalid count
     */
    public function testSetInvalidMaximumTokenCount()
    {
        try {
            $this->_formHelperToken->setMaximumTokenCount(-100);
            $this->fail('An expected Idun_Form_Helper_Exception has not been raised.');
        } catch (Idun_Form_Helper_Exception $exception) {
            $this->assertEquals('Maximum token count must be greater or equal 1.', $exception->getMessage());
        }
    }
    
    /**
     * tests hasToken()
     */
    public function testAddAndHasToken()
    {
        $this->assertFalse($this->_formHelperToken->hasToken('dummy-token'));
        $this->_formHelperToken->addToken('dummy-token');
        $this->assertTrue($this->_formHelperToken->hasToken('dummy-token'));
    }
    
    /**
     * tests addToken() with reaching maximum token count set by setMaximumTokenCount()
     */
    public function testAddMoreTokensThanMaximumTokenCount()
    {
        $this->_formHelperToken->setMaximumTokenCount(3)
            ->addToken('TokenOne')
            ->addToken('TokenTwo')
            ->addToken('TokenThree');
        
        $this->assertTrue($this->_formHelperToken->hasToken('TokenOne'));
        $this->assertTrue($this->_formHelperToken->hasToken('TokenTwo'));
        $this->assertTrue($this->_formHelperToken->hasToken('TokenThree'));
        
        $this->_formHelperToken->addToken('TokenFour');
        
        $this->assertFalse($this->_formHelperToken->hasToken('TokenOne'));
        $this->assertTrue($this->_formHelperToken->hasToken('TokenTwo'));
        $this->assertTrue($this->_formHelperToken->hasToken('TokenThree'));
        $this->assertTrue($this->_formHelperToken->hasToken('TokenFour'));
    }
    
    /**
     * tests removeToken()
     */
    public function testRemoveToken()
    {
        $this->_formHelperToken->addToken('my-dummy-token');
        $this->assertTrue($this->_formHelperToken->hasToken('my-dummy-token'));
        $this->_formHelperToken->removeToken('my-dummy-token');
        $this->assertFalse($this->_formHelperToken->hasToken('my-dummy-token'));
    }
    
    /**
     * tests createToken()
     */
    public function testCreateToken()
    {
        $token = $this->_formHelperToken->createToken();
        $this->assertEquals(32, strlen($token));
        
        $token = $this->_formHelperToken->createToken(16);
        $this->assertEquals(16, strlen($token));
    }
    
    /**
     * tests parseTokenIntoHtml()
     */
    public function testParseTokenIntoHtml()
    {
        $html = '<form method="get" action="/"></form>';
        $parsed = $this->_formHelperToken->parseTokenIntoHtml($html, 'get-form-token');
        
        $this->assertEquals(
            $html,
            $parsed
        );
        
        $this->assertFalse($this->_formHelperToken->hasToken('get-form-token'));
        
        $html = '<form method="post" action="/"></form>';
        $parsed = $this->_formHelperToken->parseTokenIntoHtml($html, 'post-form-token');
        
        $this->assertEquals(
            '<form method="post" action="/"><div><input type="hidden" name="formToken" value="post-form-token" /></div></form>',
            $parsed
        );
        
        $this->assertTrue($this->_formHelperToken->hasToken('post-form-token'));
        
        $html = '<form method="post" action="/"></form>';
        $parsed = $this->_formHelperToken->parseTokenIntoHtml($html);
        
        $this->assertContains(
            '<div><input type="hidden" name="formToken" value="',
            $parsed
        );
    }
}
