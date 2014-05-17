<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\User;

class DefaultControllerTest extends WebTestCase
{

    public function testIndex()
    {
		$session = new Session(new MockFileSessionStorage());
	
	    $client = static::createClient();
		
		$user = new User();
		$user->setUsername('Admin');
		$user->setPassword('12345');

		$token = new UsernamePasswordToken(
				$user, null, 'main', $user->getRoles()
			);
		
		$container = $client->getContainer();
		$container->get('security.context')->setToken( $token );
		
		$session = $container->get('session');
		$session->set('_security_secured_area', serialize($token));
		$session->set('userid', 23);
		$session->save();
	
		 	
		$crawler = $client->request('GET', '/title');
		$this->assertTrue($client->getResponse()->isSuccessful());
		
		$crawler = $client->request('GET', '/tickets');
		$this->assertTrue($crawler->filter('html:contains("tb_menu")')->count() > 0);		
		
		$crawler = $client->request('GET', '/alerts');
		$this->assertTrue(  TestHelper::uiarray( $client->getResponse()->getContent()));		
		
		$crawler = $client->request('GET', '/ftickets');
		$this->assertTrue($crawler->filter('html:contains("filter")')->count() > 0);	
		
		$crawler = $client->request('GET', '/filter_more');
		$this->assertTrue($crawler->filter('html:contains("formcard_src")')->count() > 0);		
		
	}	
	
}