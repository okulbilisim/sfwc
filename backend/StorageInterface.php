<?php

/**
 * SFWC v0.1
 *
 * interface file for backends
 * 
 *
 * @file        StorageInterface.class.php
 * @author      Emre Yilmaz <mail@emreyilmaz.me>
 * @license     GNU General Public License 
 * @link        http://www.emreyilmaz.me/projects/sfwc/
 * @version     0.1
 */

interface StorageInterface
{
    /**
    * creates a new token and writes is to $token
    * 
    * @access public
    * @return string
    */  
    public function createToken($descriptor);
    
    /**
    * clears token for page descriptor
    * 
    * @access public
    * @return string
    */
    public function flushSession($descriptor);

    /**
    * retrieve token by descriptor
    * 
    * @access public
    * @return array
    */
    public function getTokenByDescriptor($descriptor);
}


?>
