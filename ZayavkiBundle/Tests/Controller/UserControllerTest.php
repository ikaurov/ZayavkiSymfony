<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\User;

/**


    path:     /user_id/{id}
    path:     /user_save/{id}/{list}

*/

class UserControllerTest extends WebTestCase
{
	
	public function testActions()
    {
		$client = static::createClient();			
		
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			
		$rows = $em->getRepository('AcmeZayavkiBundle:User')->findUsersList( 0 );
		$this->assertTrue(is_array($rows));
		foreach ($rows as $rs) {
			$this->testRepository($rs['id']);
			$this->assertTrue( TestHelper::check_data( $client, '/user_tsglist/'.$rs['id'],  -1)['result'] );			
		}
		
		$id = $this->testOpen(1, 0);
		
		$id = $this->testOpen(1, $id);
				
		$this->testDelete( $id );	
		
	}
	

    public function testOpen($dummy = 0,  $id = 0)
    {			
		if ($dummy  > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			$entity = $em->getRepository('AcmeZayavkiBundle:User')->findEntity($id);
			
			$crawler = $client->request('GET', '/user_id/'.$entity['id'] );

			$entity['name']     = 'NEW User '.rand(0, 10000);
			$entity['username'] = 'NEW User '.rand(0, 10000);
			$entity['password'] = 'NEW User '.rand(0, 10000);			
			
			$crawler = $client->request('POST', '/user_save/'.(int)$entity['id'], 
							array( 'user' => $entity) 
						);
			$res = TestHelper::check_result( $client->getResponse()->getContent());	
		
			$this->assertTrue( $res['result'] == 'true' ); 
			$entity['id'] = $res['id'];
			
			$found = false;
			$rows = $em->getRepository('AcmeZayavkiBundle:User')->findUsersList( 0 );
			foreach ($rows as $rs) {
				if ($rs['id'] == $entity['id']) {
					$this->assertEquals( $entity['name'], $rs['title']);	
					$found = true;
					break;
				}		
			}
			$this->assertTrue( $found ); 
					
			return $entity['id'];	
		}
    }


	
	public function testRepository($userid = 0)
	{
		if ($userid > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			
			$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($userid, 'easyui');
			$this->assertTrue(is_array($rows));
			$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($userid, 'hash');
			$this->assertTrue(is_array($rows));
			$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->findTsgsListForUser($userid, 'array');
			$this->assertTrue(is_array($rows));		
		}
	}


	/**
		path:     /users
    */
    public function testIndex()
    {
	    $client = static::createClient();
				
        $crawler = $client->request('GET', '/users');
		$this->assertTrue($crawler->filter('html:contains("users_list")')->count() > 0);	
	}
	/**
		path:     /users_data/{tsg}
    */
    public function testData()
    {	
	    $client = static::createClient();
        $this->assertTrue( TestHelper::check_data( $client, '/users_data/0',  -1)['result'] );
		$this->assertTrue( TestHelper::check_data( $client, '/users_data/34',  -1)['result'] );
    }
	
	/**
		path:     /user_delete/{id}
	*/
    public function testDelete( $id = 0, $tsg = 0)
    {	
		if ($id  > 0) {
			$client = static::createClient();
			$crawler = $client->request('GET', '/user_delete/'.$id );
			$res = TestHelper::check_result( $client->getResponse()->getContent());		
			$this->assertTrue( $res['result'] == 'true' ); 		
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			
			$found = true;
			$rows = $em->getRepository('AcmeZayavkiBundle:User')->findUsersList( 0 );
			foreach ($rows as $rs) {
				if ($rs['id'] == $id) {
					$found = false;
					break;
				}		
			}			
			$this->assertTrue( $found ); // deleted successfully	
		}
    }	
}