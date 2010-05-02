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
class Idun_Validate_Xor extends Idun_Validate_Conditionable
{
    /**
     * @access public
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        foreach ($this->getConditionals() as $conditional)
        {
            if (!isset($valid)) {
                $valid = $current = (int)$conditional->isValid($value);
            } else {
                $valid ^= $current = (int)$conditional->isValid($value);
            }
            
            if (!$current) {
                $this->_messages = array_merge(
                    $this->_messages,
                    $conditional->getMessages()
                );
                
                $this->_errors = array_merge(
                    $this->_errors,
                    $conditional->getErrors()
                );
            }
        }
        
        if ($valid) {
            $this->_messages = array();
            $this->_errors = array();
            return true;
        }
        
        return false;
    }
}
