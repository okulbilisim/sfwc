<?php

include_once('StorageInterface.php');

/**
 * 
 * mongodb based storage backend for  SFWC
 * 
 */
class CSRFTokenDatabaseStorage implements StorageInterface {

    /**
     * variable that holds latest generated token.
     * 
     * @var string
     * @access public
     */
    public $token;

    /**
     * variable that holds mongodb \connection\collection
     * 
     * @var string
     * @access private
     */
    private $collection;

    public function __construct() {
        if (class_exists("Mongo") === False) {
            echo("mongodb driver doesn't exists in your PHP configuration. install first.");
            exit();
        }
        $connection = new Mongo();
        $database = $connection->tokens;
        $this->collection = $database->token;
    }

    public function createToken($descriptor) {

        $doc = $this->collection->findone(array('descriptor' => $descriptor));
        if (isset($doc["token"]) && $doc["time"] + SFWC::$expireTime > time()) {
            $this->token = $doc["token"];
            return $this->token;
        }

        /*
          new random token for session and base class
         */
        $key = sha1(uniqid(rand(), true) . SFWC::$secret);
        $this->token = $key;

        $doc = array(
            "descriptor" => $descriptor,
            "token" => $key,
            "time" => time(),
        );

        $this->collection->remove(array('descriptor' => $descriptor));
        $this->collection->insert($doc, true);

        return $doc["token"];
    }

    public function flushSession($descriptor) {
        $this->collection->remove(array('descriptor' => $descriptor));
    }

    public function getTokenByDescriptor($descriptor) {
        $doc = $this->collection->findOne(array("descriptor" => $descriptor));
        return $doc;
    }

}
