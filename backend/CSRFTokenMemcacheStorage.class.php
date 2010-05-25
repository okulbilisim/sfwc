<?php

include_once('StorageInterface.php');

/**
 * SFWC v0.1
 *
 * Memcache based storage backend for  SFWC
 * 
 *
 * @file        CSRFTokenSessionStorage.class.php
 * @author      Emre Yilmaz <mail@emreyilmaz.me>
 * @license     GNU General Public License 
 * @link        http://www.emreyilmaz.me/projects/sfwc/
 * @version     0.1
 */

class CSRFTokenMemcacheStorage implements StorageInterface {

    /**
    * variable that holds latest generated token.
    * 
    * @var string
    * @access public
    */
    public $token;

    /**
    * variable that holds memcache connection.
    * 
    * @var object
    * @access public
    */
    public $connection;

    public $memcacheKey = 'csrf_token_%s'; # % descriptor
    
    public function __construct() {
        $this->connection = new Memcache;
        $link = $this->connection->connect('127.0.0.1', 11211);
    }

    public function createToken($descriptor) {
        /*
        if token is not expired, dont create new one.
        */
        $oldToken = $this->connection->get(sprintf($this->memcacheKey, $descriptor));

        if(is_array($oldToken) && $oldToken["time"] + SFWC::$expireTime > time()) {
            $this->token = $oldToken["token"];
            return $this->token;
        }
        
        /*
        new random token for session and base class
        */

        $key = sha1(uniqid(rand(), true) . SFWC::$secret);
        $this->token = $key;
        
        $data = array(
            "token" => $key,
            "time"  => time(),
        );
        $this->connection->set(sprintf($this->memcacheKey, $descriptor), $data, MEMCACHE_COMPRESSED, SFWC::$expireTime);        

        return $key;
    }

    public function flushSession($descriptor) {
        $this->connection->delete(sprintf($this->memcacheKey, $descriptor));
    }

    public function getTokenByDescriptor($descriptor) {
        $token = $this->connection->get(sprintf($this->memcacheKey, $descriptor));
        return $token;
    }

}

?>
