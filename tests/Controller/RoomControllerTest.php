<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class RoomControllerTest extends WebTestCase
{
	protected function setUp()
{
    $this->client = static::createClient();

    $this->logIn();
}

private function logIn()
{
    $session = $this->client->getContainer()->get('session');

    $firewallName = 'test';
    // if you don't define multiple connected firewalls, the context defaults to the firewall name
    // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
    $firewallContext = 'test';

    // you may need to use a different token class depending on your application.
    // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
	 $testUser = new User('test@test.com', 'name', 'surename', 'en');
	 $testUser->setRoles(['ROLE_GUIDE']);
	 
    $token = new UsernamePasswordToken($testUser, null, $firewallName, ['ROLE_GUIDE']);
   //  var_dump($token);
    $session->set('_security_' . $firewallContext, serialize($token));
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $this->client->getCookieJar()->set($cookie);
}

	public function testRoomCreate()
	{
		// $client = static::createClient();

		$this->client->request('POST', '/room/create', [
			'name' => 'name',
			'description' => 'description'
		]);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}
}