<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    private $username = 'guide@guide.com';
    private $password = 'test';

    /**
     * Message create unit test
     */
	public function testMessageCreate()
	{
		$client = static::createClient([], [
            'PHP_AUTH_USER' => $this->username,
            'PHP_AUTH_PW' => $this->password,
        ]);

		$client->request('POST', '/message/create', [
			'room_id' => 3,
			'contents' => 'this is test message',
			'receiver_id' => [
				1,
				2,
			]
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}