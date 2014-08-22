<?php

use Guzzle\Http\Client;

class FormTest extends PHPUnit_Framework_TestCase {

    /**
     * Test is form get
     */
    public function testFormGet() {
        $client = new Client('https://localhost');
        $request = $client->get('/get');
        $decodedResponse = $response->json();
        $this->assertEquals($decodedResponse['status']['csrf'], 'false');
    }

    /**
     * Tests form post
     */
    public function testFormPost() {
        $client = new Client('https://localhost');
        $request = $client->post('/post');
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($decodedResponse['status']['csrf'], 'false');
    }

}
