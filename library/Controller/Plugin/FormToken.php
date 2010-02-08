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
        if (!$formHelperToken instanceof Idun_Form_Helper_Token) {
            $formHelperToken = new Idun_Form_Helper_Token($formHelperToken);
        }
        $this->_formHelperToken = $formHelperToken;
    }
    
    /**
     * @access public
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isPost()) {
            return null;
        }
        
        $token = $request->getPost($this->_formHelperToken->getTokenKey());
        if (!empty($token) && $this->_formHelperToken->hasToken($token)) {
            $this->_formHelperToken->removeToken($token);
            return null;
        }
        
        $this->_checkFailed($request);
    }
    
    /**
     * @access public
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $response->setBody($this->_formHelperToken->parseTokenIntoHtml(
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
