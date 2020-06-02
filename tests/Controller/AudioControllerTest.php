<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

		$audio = new UploadedFile(
			'path/audio.wav',
			'audio.wav',
			'audio/wav',
			24
		);
		
		$client->request('POST', '/api/audio/create', [
			'room_id' => 5,
			'audio' => $audio,
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

		$client->request('POST', '/api/audio/3/delete');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}