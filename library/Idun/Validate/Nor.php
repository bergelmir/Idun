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
 * @package   Idun_Validate
 * @copyright Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version   $Id:$
 */
 
/**
 * @category  Idun
 * @package   Idun_Validate
 * @copyright Copyright (c) 2010 Arvid Bergelmir
 * @author    Arvid Bergelmir <arvid.bergelmir@flabben.net>
 */
class Idun_Validate_Nor extends Idun_Validate_Or
{
    /**
     * Error codes
     */
    const ONE_OR_MORE_CONDITIONS_TRUE = 'oneOrMoreConditionsTrue';
    
    /**
     * @access protected
     * @var    array
     */
    protected $_messageTemplates = array(
        self::ONE_OR_MORE_CONDITIONS_TRUE => '\'%value%\' matches one or more validators'
    );
    
    /**
     * @access public
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (parent::isValid($value)) {
            $this->_messages = array();
            $this->_errors = array();
            return true;
        }
        
        $this->_error(self::ONE_OR_MORE_CONDITIONS_TRUE);
        return false;
    }
}
