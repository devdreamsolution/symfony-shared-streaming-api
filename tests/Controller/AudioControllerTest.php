<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AudioControllerTest extends WebTestCase
{

	public function testAudioCreate()
	{
		$client = static::createClient();

		$client->request('POST', '/audio/create', [
			'room_id' => 5
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}