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
abstract class Idun_Validate_Conditionable extends Zend_Validate_Abstract
{
    /**
     * @access protected
     * @var    array
     */
    protected $_conditionals = array();
    
    /**
     * @access public
     * @return void
     */
    public function __construct()
    {
        if (($count = count($conditionals = func_get_args())) > 0) {
            if (($count == 1) && is_array($conditionals[0])) {
                $this->setConditionals($conditionals[0]);
            } else {
                $this->setConditionals($conditionals);
            }
        }
    }
    
    /**
     * @access public
     * @param  array $conditionals
     * @return Idun_Validate_Conditionable
     */
    public function setConditionals(array $conditionals)
    {
        $this->clearConditionals();
        $this->addConditionals($conditionals);
        return $this;
    }
    
    /**
     * @access public
     * @param  array $conditionals
     * @return Idun_Validate_Conditionable
     */
    public function addConditionals(array $conditionals)
    {
        foreach ($conditionals as $conditional) {
            $this->addConditional($conditional);
        }
        return $this;
    }
    
    /**
     * @access public
     * @param  Zend_Validate_Interface|string|array $conditional
     * @throws Idun_Validate_Exception
     * @return Idun_Validate_Conditionable
     */
    public function addConditional($conditional)
    {
        if (is_string($conditional) || is_array($conditional)) {
            $conditional = Idun_Validate::getValidator($conditional);
        }
        
        if (!$conditional instanceof Zend_Validate_Interface) {
            throw new Idun_Validate_Exception(
                'Invalid conditional given. Must be an instance ' .
                'of Zend_Validate_Interface.'
            );
        }
        
        $this->_conditionals[] = $conditional;
        return $this;
    }
    
    /**
     * @access public
     * @return array
     */
    public function getConditionals()
    {
        return $this->_conditionals;
    }
    
    /**
     * @access public
     * @return Idun_Validate_Conditionables
     */
    public function clearConditionals()
    {
        $this->_conditionals = array();
        return $this;
    }
}
