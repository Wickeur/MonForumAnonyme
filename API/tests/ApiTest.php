<?php

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
    public function testGetMessages() {
        $url = 'http://localhost:3000/'; // Assurez-vous de mettre à jour l'URL avec celle de votre API
        $response = file_get_contents($url);

        $this->assertJson($response);

        $data = json_decode($response, true);

        $this->assertIsArray($data);
    }

    public function testAddMessage() {
        $url = 'http://localhost:3000/';
        $data = array(
            'user' => 'John Doe',
            'description' => 'Test message'
        );
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/json',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        $this->assertJson($response);

        $data = json_decode($response, true);

        $this->assertArrayHasKey('message', $data);
    }

    public function testDeleteMessage() {
        $url = 'http://localhost:3000/';
        $data = array(
            'id' => 1
        );
        $options = array(
            'http' => array(
                'method'  => 'DELETE',
                'header'  => 'Content-Type: application/json',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        $this->assertJson($response);

        $data = json_decode($response, true);

        $this->assertArrayHasKey('message', $data);
    }
}
