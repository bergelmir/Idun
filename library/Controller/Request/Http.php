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

/** @see Zend_Controller_Request_Http */
require_once 'Zend/Controller/Request/Http.php';

/**
 * @category  Idun
 * @package   Idun_Controller
 * @copyright Copyright (c) 2010 Arvid Bergelmir
 * @author    Arvid Bergelmir <arvid.bergelmir@flabben.net>
 */
class Idun_Controller_Request_Http extends Zend_Controller_Request_Http
{
    /**
     * @access public
     * @param  string  $key     Key to search for in $_GET
     * @param  mixed   $default Default value if $key is not found in $_GET
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    public function getQuery($key = null, $default = null, $filter = null, array $options = null)
    {
        $value = parent::getQuery($key, $default);
        if ($filter !== null || $options !== null) {
            $value = $this->_filter($value, $default, $filter, $options);
        }
        return $value;
    }
    
    /**
     * @access public
     * @param  string  $key     Key to search for in $_POST
     * @param  mixed   $default Default value if $key is not found $_POST
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    public function getPost($key = null, $default = null, $filter = null, array $options = null)
    {
        $value = parent::getQuery($key, $default);
        if ($filter !== null || $options !== null) {
            $value = $this->_filter($value, $default, $filter, $options);
        }
        return $value;
    }
    
    /**
     * @access public
     * @param  string  $key     Key to search for in $_SERVER
     * @param  mixed   $default Default value if $key is not found in $_SERVER
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    public function getServer($key = null, $default = null, $filter = null, array $options = null)
    {
        $value = parent::getQuery($key, $default);
        if ($filter !== null || $options !== null) {
            $value = $this->_filter($value, $default, $filter, $options);
        }
        return $value;
    }
    
    /**
     * @access public
     * @param  string  $key     Key to search for in $_COOKIE
     * @param  mixed   $default Default value if $key is not found in $_COOKIE
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    public function getCookie($key = null, $default = null, $filter = null, array $options = null)
    {
        $value = parent::getQuery($key, $default);
        if ($filter !== null || $options !== null) {
            $value = $this->_filter($value, $default, $filter, $options);
        }
        return $value;
    }
    
    /**
     * @access public
     * @param  string  $key     Key to search for in $_ENV
     * @param  mixed   $default Default value if $key is not found in $_ENV
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    public function getEnv($key = null, $default = null, $filter = null, array $options = null)
    {
        $value = parent::getQuery($key, $default);
        if ($filter !== null || $options !== null) {
            $value = $this->_filter($value, $default, $filter, $options);
        }
        return $value;
    }
    
    /**
     * Filter value by given definition and/or configuration.
     * 
     * @access protected
     * @param  mixed   $value   Value to validate
     * @param  mixed   $default Default value if $value is not valid
     * @param  integer $filter  Filter definition
     * @param  array   $options Filter configuration
     * @return mixed
     */
    protected function _filter($value, $default = null, $filter = null, array $options = null)
    {
        if ($options === null) {
            $options = array();
        }
        
        if (!empty($options['flags'])) {
            $options['flags'] |= FILTER_NULL_ON_FAILURE;
        } else {
            $options = $this->_arrayMergeRecursive($options, array(
                'flags' => FILTER_NULL_ON_FAILURE
            ));
        }
        
        if (!is_int($filter) && !is_numeric($filter)) {
            $mapping = array(
                'int'     => FILTER_VALIDATE_INT,
                'integer' => FILTER_VALIDATE_INT,
                'float'   => FILTER_VALIDATE_FLOAT,
                'double'  => FILTER_VALIDATE_FLOAT,
                'ip'      => FILTER_VALIDATE_IP,
                'url'     => FILTER_VALIDATE_URL,
                'email'   => FILTER_VALIDATE_EMAIL,
                'mail'    => FILTER_VALIDATE_EMAIL,
                'bool'    => FILTER_VALIDATE_BOOLEAN,
                'boolean' => FILTER_VALIDATE_BOOLEAN,
                'regex'   => FILTER_VALIDATE_REGEXP,
                'regexp'  => FILTER_VALIDATE_REGEXP
            );
            if (!isset($mapping[$filter])) {
                throw new Idun_Controller_Request_Exception(sprintf(
                    'Unknown filter type "%s".', $filter
                ));
            }
            $filter = $mapping[$filter];
        }
        
        if (($value = filter_var($value, $filter, $options)) === null) {
            $value = $default;
        }
        
        return $value;
    }
    
    /**
     * @access protected
     * @param  mixed $firstArray
     * @param  mixed $secondArray
     * @return array
     */
    protected function _arrayMergeRecursive($firstArray, $secondArray)
    {
        if (is_array($firstArray) && is_array($secondArray)) {
            foreach ($secondArray as $key => $value) {
                if (isset($firstArray[$key])) {
                    $firstArray[$key] = $this->_arrayMergeRecursive(
                        $firstArray[$key], $value
                    );
                } else {
                    if ($key === 0) {
                        $firstArray = array(0 => $this->_arrayMergeRecursive(
                            $firstArray, $value
                        ));
                    } else {
                        $firstArray[$key] = $value;
                    }
                }
            }
        } else {
            $firstArray = $secondArray;
        }
        return $firstArray;
    }
}
