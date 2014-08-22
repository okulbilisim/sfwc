<?php

/**
 *
 * interface file for backends
 *
 */
interface StorageInterface {

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
