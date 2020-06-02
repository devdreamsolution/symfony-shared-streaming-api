<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends WebTestCase
{
	/**
	 * User register unit test
	 */
	public function testUserRegister()
	{
		$client = static::createClient();
		$picture = new UploadedFile(
			'path/picture.jpg',
			'picture.jpg',
			'image/jpeg',
			24
		);

		$client->request('POST', '/api/user/register', [
			'username' => 'test@test.com',
			'password' => 'testPassword',
			'name' => 'testName',
			'surename' => 'sureName',
			'roles' => 1,
			'city_residence' => 'testCity',
			'group_age' => 10,
			'gender' => 1,
			'age' => 20,
			'vat' => 10.2,
			'address' => 'testAddress',
			'lang' => 'en',
			'picture' => $picture,
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}