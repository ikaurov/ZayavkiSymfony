<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\Workers;

class WorkersControllerTest extends WebTestCase
{
	
	public function testRepository()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');		
			
		$rows = $em->getRepository('AcmeZayavkiBundle:Workers')->findWorkersForTsg(0, '', 0, 'easyui', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows['rows']));
	
		$rows = $em->getRepository('AcmeZayavkiBundle:Workers')->findWorkersForTsg(0, '', 0,  'hash', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));

		$rows = $em->getRepository('AcmeZayavkiBundle:Workers')->findWorkersForTsg(0, '', 0,  'array', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));		
	
	}
	
	/**
		path:     /workers
    */
    public function testIndex()
    {
	    $client = static::createClient();
				
        $crawler = $client->request('GET', '/workers');
		$this->assertTrue($crawler->filter('html:contains("workers_list")')->count() > 0);	
	}
	/**
		path:     /workers_data/{tsg}/{kind}/{options}/{incl}
    */
    public function testData()
    {	
	    $client = static::createClient();
        $this->assertTrue( TestHelper::check_data( $client, '/workers_data/0/0/N/0',  -1)['result'] );
		$this->assertTrue( TestHelper::check_data( $client, '/workers_data/0/1/A/0',  -1)['result'] );
    }
	
    public function testActions()
    {	
	
	// no tsg
		$tsg = 0;
		$id = $this->testOpen(1,0,$tsg);
		$id = $this->testOpen(1, $id, $tsg); // update
		$this->testDelete( $id, $tsg );	
		
	// head = 1
		$tsg = 34;
		$id = $this->testOpen(1,0,$tsg);
		$id = $this->testOpen(1, $id, $tsg); // update
		$this->testDelete( $id, $tsg );	
		
	//head = 0
		$tsg = 23;
		$id = $this->testOpen(1,0,$tsg);
		$id = $this->testOpen(1, $id, $tsg); // update
		$this->testDelete( $id, $tsg );	
		
    }	
	
	/**
		path:     /workers_id/{tsg}/{id}
		path:     /workers_save/{tsg}/{id}
	*/
    public function testOpen($dummy = 0,  $id = 0, $tsg = 0)
    {			
		if ($dummy  > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			$entity = $em->getRepository('AcmeZayavkiBundle:Workers')->findEntity($id);
			
			$crawler = $client->request('GET', '/workers_id/'.$tsg.'/'.$entity['id'] );

			$entity['name'] = 'NEW Worker '.rand(0, 10000);
			$entity['ownid']= $tsg;
			$crawler = $client->request('POST', '/workers_save/'.$tsg.'/'.(int)$entity['id'], 
							array( 'worker' => $entity) 
						);
			$res = TestHelper::check_result( $client->getResponse()->getContent());	

			$this->assertTrue( $res['result'] == 'true' ); 
			$entity['id'] = $res['id'];
					
			$crawler = $client->request('GET', '/workers_data/'.$tsg.'/0/N/0');	
			$var = TestHelper::check_data( $client, '/workers_data/'.$tsg.'/0/N/0',  $entity['id']);	
			
			$this->assertEquals( $entity['name'], $var['item']['name']);
					
			return $entity['id'];	
		}
    }   

	/**
		path:     /workers_delete/{id}
	*/
    public function testDelete( $id = 0, $tsg = 0)
    {	
		if ($id  > 0) {
			$client = static::createClient();
			$crawler = $client->request('GET', '/workers_delete/'.$id );
			$res = TestHelper::check_result( $client->getResponse()->getContent());		
			$this->assertTrue( $res['result'] == 'true' ); 		
			
			$res = TestHelper::check_data( $client, '/workers_data/'.$tsg,  $id);
			$this->assertTrue( $res['result'] == 'false' ); // deleted successfully	
		}
    }	
		
}