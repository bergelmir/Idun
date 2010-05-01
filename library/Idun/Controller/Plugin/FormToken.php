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
 * @category  Idun
 * @package   Idun_Controller
 * @copyright Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version   $Id:$
 */

/**
 * @category  Idun
 * @package   Idun_Controller
 * @copyright Copyright (c) 2010 Arvid Bergelmir
 * @author    Arvid Bergelmir <arvid.bergelmir@flabben.net>
 */
class Idun_Controller_Plugin_FormToken extends Zend_Controller_Plugin_Abstract
{
    /**
     * @access protected
     * @var    Idun_Form_Helper_Token
     */
    protected $_formHelperToken = null;
    
    /**
     * @access public
     * @param  Idun_Form_Helper_Token|Zend_Config|array|null $formHelperToken
     * @return void
     */
    public function __construct($formHelperToken = null)
    {
        if ($formHelperToken instanceof Idun_Form_Helper_Token) {
            $this->setHelper($formHelperToken);
        } elseif (is_array($formHelperToken) || ($formHelperToken instanceof Zend_Config)) {
            $this->setHelper(new Idun_Form_Helper_Token($formHelperToken));
        } elseif ($formHelperToken !== null) {
            throw new Idun_Controller_Plugin_Exception(sprintf(
                'First parameter must be NULL, an array, an instance of Zend_Config ' .
                'or an instance of Idun_Form_Helper_Token. %s given.',
                ucfirst(gettype($formHelperToken))
            ));
        }
    }
    
    /**
     * @access public
     * @param  Idun_Form_Helper_Token $formHelperToken
     * @return Idun_Controller_Plugin_Token
     */
    public function setHelper(Idun_Form_Helper_Token $formHelperToken)
    {
        $this->_formHelperToken = $formHelperToken;
        return $this;
    }
    
    /**
     * @access public
     * @param  boolean $required
     * @return Idun_Form_Helper_Token|null
     */
    public function getHelper($required = true)
    {
        if ($required && empty($this->_formHelperToken)) {
            throw new Idun_Controller_Plugin_Exception('No token helper set.');
        }
        return $this->_formHelperToken;
    }
    
    /**
     * @access public
     * @param  Zend_Controller_Request_Abstract $request
     * @return boolean|null
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isPost()) {
            return null;
        }
        
        $formHelperToken = $this->getHelper();
        $token = $request->getPost($formHelperToken->getTokenKey());
        if (!empty($token) && $formHelperToken->hasToken($token)) {
            $formHelperToken->removeToken($token);
            return true;
        }
        
        $this->_checkFailed($request);
        return false;
    }
    
    /**
     * @access public
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $response->setBody($this->getHelper()->parseTokenIntoHtml(
            $response->getBody()
        ));
    }
    
    /**
     * @access protected
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    protected function _checkFailed(Zend_Controller_Request_Abstract $request)
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        foreach ($_POST as $key => $value) {
            unset($_POST[$key]);
        }
    }
}
