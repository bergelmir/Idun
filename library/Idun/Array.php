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
 * @package   Idun_Array
 * @copyright Copyright (c) 2010 Arvid Bergelmir (http://www.flabben.net/)
 * @version   $Id:$
 */

/**
 * @category  Idun
 * @package   Idun_Array
 * @copyright Copyright (c) 2010 Arvid Bergelmir
 * @author    Arvid Bergelmir <arvid.bergelmir@flabben.net>
 */
class Idun_Array
{
    /**
     * @static
     * @access public
     * @param  array $firstArray
     * @param  array $secondArray
     * @return array
     */
    public static function mergeRecursive(array $firstArray, array $secondArray)
    {
        foreach ($secondArray as $key => $value) {
            if (isset($firstArray[$key]) && is_array($value)) {
                $firstArray[$key] = self::mergeRecursive(
                    is_array($firstArray[$key]) ? $firstArray[$key] : array(),
                    $value
                );
            } else {
                $firstArray[$key] = $value;
            }
        }
        return $firstArray;
    }
}
