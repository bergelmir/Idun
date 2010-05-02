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
class Idun_Validate extends Zend_Validate
{
    /**
     * @static
     * @access protected
     * @var    Zend_Loader_PluginLoader_Interface
     */
    protected static $_pluginLoader = null;
    
    /**
     * @static
     * @access public
     * @param  Zend_Loader_PluginLoader_Interface $pluginLoader
     * @return void
     */
    public static function setPluginLoader(Zend_Loader_PluginLoader_Interface $pluginLoader)
    {
        self::$_pluginLoader = $pluginLoader;
    }
    
    /**
     * @static
     * @access public
     * @return Zend_Loader_PluginLoader_Interface
     */
    public static function getPluginLoader()
    {
        if (self::$_pluginLoader === null) {
            self::setPluginLoader(new Zend_Loader_PluginLoader(array(
                'Zend_Validate_' => 'Zend/Validate/'
            )));
        }
        return self::$_pluginLoader;
    }
    
    /**
     * @static
     * @access public
     * @param  string|array $validator
     * @throws Idun_Validate_Exception
     * @return Zend_Validate_Interface
     */
    public static function getValidator($validator)
    {
        if (is_string($validator)) {
            $className = $validator;
        } elseif (is_array($validator) && !empty($validator['validator'])) {
            $className = $validator['validator'];
        } else {
            throw new Idun_Validate_Exception('No validator class name given.');
        }
        
        if (strpos($className, '_') === false) {
            $className = self::getPluginLoader()->load($className);
        }
        
        $reflection = new ReflectionClass($className);
        if (!$reflection->implementsInterface('Zend_Validate_Interface')) {
            throw new Idun_Validate_Exception(sprintf(
                'Validator class "%s" must implement Zend_Validate_Interface.',
                $className
            ));
        }
        
        if (is_string($validator)) {
            return new $className;
        }
        
        $options = array();
        if (!empty($validator['options'])) {
            $options = $validator['options'];
        }
        
        $messages = false;
        if (is_array($options) && array_key_exists('messages', $options)) {
            $messages = $options['messages'];
            unset($options['messages']);
        } elseif (array_key_exists('messages', $validator)) {
            $messages = $validator['messages'];
            unset($validator['messages']);
        }
        
        if (!empty($options)) {
            if ($reflection->hasMethod('__construct')) {
                $numeric = false;
                if (is_array($validator['options'])) {
                    $optionKeys = array_keys($options);
                    foreach ($optionKeys as $optionKey) {
                        if (is_int($optionKey)) {
                            $numeric = true;
                            break;
                        }
                    }
                }
                if ($numeric) {
                    $instance = $reflection->newInstanceArgs($options);
                } else {
                    $instance = $reflection->newInstance($options);
                }
            }
        }
        
        if (empty($instance)) {
            $instance = new $className;
        }
        
        if (!empty($messages)) {
            if (is_array($messages)) {
                $instance->setMessages($messages);
            } elseif (is_string($messages)) {
                $instance->setMessage($messages);
            }
        }
        
        return $instance;
    }
    
    /**
     * @static
     * @access public
     * @param  mixed  $value
     * @param  string $classBaseName
     * @param  array  $args
     * @param  array  $namespaces
     * @throws Idun_Validate_Exception
     * @return boolean
     */
    public static function is($value, $classBaseName, array $args = array(), $namespaces = array())
    {
        $namespaces = array_merge(
            (array)$namespaces,
            self::$_defaultNamespaces,
            array('Zend_Validate')
        );
        
        $className = ucfirst($classBaseName);
        if (!class_exists($className, false)) {
            $className = self::_getValidatorClassName($className, $namespaces);
        }
        
        if (empty($className)) {
            throw new Idun_Validate_Exception(sprintf(
                "Validate class not found from basename '%s'",
                $classBaseName
            ));
        }
        
        return self::getValidator(array(
            'validator' => $className,
            'options'   => $args
        ))->isValid($value);
    }
    
    /**
     * @static
     * @access protected
     * @param  string $classBaseName
     * @param  array  $namespaces
     * @return string|null
     */
    protected static function _getValidatorClassName($classBaseName, array $namespaces)
    {
        require_once 'Zend/Loader.php';
        foreach ($namespaces as $namespace) {
            $class = rtrim($namespace, '_') . '_' . $classBaseName;
            $file  = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            if (Zend_Loader::isReadable($file)) {
                Zend_Loader::loadClass($class);
                return $class;
            }
        }
        return null;
    }
}
