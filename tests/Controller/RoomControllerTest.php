<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomControllerTest extends WebTestCase
{
    private $username = 'guide@guide.com';					// Only the user who has ROLE_GUIDE
    private $password = 'test';

    /**
     * Room create unit test
     */
	public function testRoomCreate()
	{
		$client = static::createClient([], [
            'PHP_AUTH_USER' => $this->username,
            'PHP_AUTH_PW' => $this->password,
        ]);

		$client->request('POST', '/api/room/create', [
			'name' => 'name',
			'description' => 'description'
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/**
	 * Room delete unit test
	 */
	public function testRoomDelete()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => $this->username,
			'PHP_AUTH_PW' => $this->password,
		]);

		$client->request('POST', '/api/room/3/delete');
		
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}
