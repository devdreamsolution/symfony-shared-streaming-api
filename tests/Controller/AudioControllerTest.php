<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AudioControllerTest extends WebTestCase
{
	private $username = 'guide@guide.com';					// Only the user who has ROLE_GUIDE
	private $password = 'test';

	/**
	 * Audio create unit test
	 */
	public function testAudioCreate()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => $this->username,
			'PHP_AUTH_PW' => $this->password,
		]);

		$client->request('POST', '/audio/create', [
			'room_id' => 5
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/**
	 * Audio delete unit test
	 */
	public function testAudioDelete()
	{
		$client = static::createClient([], [
			'PHP_AUTH_USER' => $this->username,
			'PHP_AUTH_PW' => $this->password,
		]);

		$client->request('POST', '/audio/3/delete');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}