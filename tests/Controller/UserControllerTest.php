<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
	/**
	 * User register unit test
	 */
	public function testUserRegister()
	{
		$client = static::createClient();

		$client->request('POST', '/user/register', [
			'username' => 'test@test.com',
			'password' => 'testPassword',
			'name' => 'testName',
			'surename' => 'sureName',
			'roles' => 0,
			'city_residence' => 'testCity',
			'group_age' => 10,
			'gender' => 1,
			'age' => 20,
			'vat' => 10.2,
			'address' => 'testAddress',
			'lang' => 'en'
		]);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}