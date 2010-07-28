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
 * @package   Idun_Debug
 * @copyright Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version   $Id:$
 */

/**
 * @category  Idun
 * @package   Idun_Debug
 * @copyright Copyright (c) 2010 Arvid Bergelmir
 * @author    Arvid Bergelmir <arvid.bergelmir@flabben.net>
 */
class Idun_Debug extends Zend_Debug
{
    /**
     * Example CSS:
     *   http://github.com/bergelmir/Idun/blob/master/files/idun_debug.css
     * 
     * @static
     * @access public
     * @param  mixed       $var
     * @param  string|null $label
     * @param  boolean     $echo
     * @return string
     */
    public static function dump($var, $label = null, $echo = true)
    {
        if (!$echo) {
            return parent::dump($var, $label, $echo);
        }
        
        $backtrace = debug_backtrace();
        
        $location = sprintf(
            'Called in %s on line %d',
            $backtrace[0]['file'],
            $backtrace[0]['line']
        );
        
        echo $output =
            '<div class="idun_debug">' .
                '<div class="info">' .
                    '<div class="label">' . (!empty($label) ? $label : '') . '</div>' .
                    '<div class="location">' . $location . '</div>' .
                    '<div class="clear"></div>' .
                '</div>' .
                '<div class="dump">' .
                    parent::dump($var, null, false) .
                '</div>' .
             '</div>';
        
        return $output;
    }
}
