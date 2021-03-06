<?php

include_once('StorageInterface.php');

/**
 *
 * Session based storage backend for  SFWC
 * 
 */
class CSRFTokenSessionStorage implements StorageInterface {

    /**
     * variable that holds latest generated token.
     * 
     * @var string
     * @access public
     */
    public $token;

    public function __construct() {
        /* if session is not initialized, don't start again */
        if (session_id() == "") {
            session_start();
        }
    }

    public function createToken($descriptor) {
        /*
          if token is not expired, dont create new one.
         */
        if (!empty($_SESSION["csrf_tokens"][$descriptor])) {
            if ($_SESSION["csrf_tokens"][$descriptor]["time"] + SFWC::$expireTime > time()) {
                $this->token = $_SESSION["csrf_tokens"][$descriptor]["token"];
                return $this->token;
            }
        }

        /*
          new random token for session and base class
         */

        $key = sha1(uniqid(rand(), true) . SFWC::$secret);
        $this->token = $key;

        $_SESSION["csrf_tokens"][$descriptor] = array(
            "token" => $key,
            "time" => time(),
        );

        return $_SESSION["csrf_tokens"][$descriptor]["token"];
    }

    public function flushSession($descriptor) {
        unset($_SESSION["csrf_tokens"][$descriptor]);
    }

    public function getTokenByDescriptor($descriptor) {
        return $_SESSION["csrf_tokens"][$descriptor];
    }

}
