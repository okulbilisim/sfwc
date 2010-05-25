<?php
session_start();

include 'backend/CSRFTokenSessionStorage.class.php';
/*
    if you want to use mongodb as backend include this
    include 'backend/CSRFTokenDatabaseStorage.class.php';
*/

/**
 * SFWC v0.1
 *
 * (S)ecure (F)orms (W)ith (C)emil is a tiny php class
 * for preventing CSRF attacks easily.
 * 
 *
 * @file        sfwc_main.php
 * @author      Emre Yilmaz <mail@emreyilmaz.me>
 * @license     GNU General Public License 
 * @link        http://www.emreyilmaz.me/projects/sfwc/
 * @version     0.1
 */

class SFWC {
    
    const driver = 'CSRFTokenSessionStorage'; 
    
    /**
    * variable that holds single instance for singleton pattern.
    * 
    * @var null
    * @access private
    */
    private static $instance = null;
    
    /**
    * variable that holds related driver's instance for storage backend
    * 
    * @var null
    * @access public
    */
    
    public $backend = null;
    
    /**
    * variable that holds single instance for singleton pattern.
    * 
    * @var null
    * @access private
    */
    
    /**
    * secret key for creating tokens
    * 
    * @var string
    * @access public
    */
    public $secret = "a76s9d781;asdkl~12*";

    /**
    * token string
    * 
    * @var string
    * @access public
    */
    public $token = "";
    
    /**
    * expire time for tokens
    * 
    * @var integer
    * @access public
    */
    public static $expireTime = 120;

    /**
    * in order to prevent more than one instance of this class,
    * "cemil" makes __construct() function private.
    * 
    * @access private
    * @return void
    */    
    private function __construct() {}
    
    /**
    * returns the instance of this class.
    * 
    * @access public
    */  
    public static function get() {
        if(self::$instance === null) {
            self::$instance = new SFWC();
            $driver = self::driver;
            self::$instance->backend  = new $driver();
        }
        return self::$instance;
    }

    /**
    * creates a new token for page descriptor if it doesn't exists
    * 
    * @access public
    * @return self
    */  
    public function initToken($descriptor) {
        $this->token = $this->backend->createToken($descriptor);
        return $this;
    }
    
    /**
    * getter function for latest token
    * 
    * @access public
    * @return string
    */  
    public function getToken() {
        return $this->token;
    }

    /**
    * returns the token as HTML input element.
    * 
    * @access public
    * @return string
    */  
    public function getTokenAsHtml() {
        return sprintf('<input type="hidden" name="csrf_token" value="%s" />', $this->getToken());
    }
    
    /**
    * setter function for expireTime (seconds)
    * 
    * @access public
    * @return string
    */
    public function setExpireTime($expireTime) {
        self::$expireTime = $expireTime;
    }
    
    /**
    * clears token for page descriptor
    * 
    * @access public
    * @return string
    */  
    private function flushSession($descriptor) {
        $this->backend->flushSession($descriptor);
        $this->token = $this->backend->createToken($descriptor);
    }
    
    /**
    * controls token for the given value
    * 
    * @access public
    * @return string
    */      
    public function controlToken($token, $descriptor) {
        
        $_token = $this->backend->getTokenByDescriptor($descriptor);

        if(time() > $_token["time"] + self::$expireTime) {
            return False;
        }
        
        if($_token["token"] != $token || empty($token)) {
            return False;
        }
        
        /* yikes */
        $this->flushSession($descriptor);
        
        return True;
    }
}

?>