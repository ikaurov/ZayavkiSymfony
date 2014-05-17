<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\Profs;

class ProfsControllerTest extends WebTestCase
{
	
	public function testRepository()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');		
			
		$rows = $em->getRepository('AcmeZayavkiBundle:Profs')->findProfsList('', 'easyui', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows['rows']));
		$rows = $em->getRepository('AcmeZayavkiBundle:Profs')->findProfsList('', 'hash', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));
		$rows = $em->getRepository('AcmeZayavkiBundle:Profs')->findProfsList('', 'array', $kernel->getContainer()->get('transloc')->getTranslated('B'));		
		$this->assertTrue(is_array($rows));	
	}
	
	/**
		path:     /profs
	*/
    public function testIndex()
    {
	    $client = static::createClient();
				
        $crawler = $client->request('GET', '/profs');
		$this->assertTrue($crawler->filter('html:contains("profs_list")')->count() > 0);	
	}
	/**
		path:     /profs_data
	*/	
    public function testData()
    {	
	    $client = static::createClient();
        $this->assertTrue( TestHelper::check_data( $client, '/profs_data',  -1)['result'] );
    }
	
    public function testActions()
    {	
	
		$id = $this->testOpen(1,0);
		$id = $this->testOpen(1, $id); // update
		$this->testDelete( $id );	
    }	
	
	/**
		path:     /profs_id/nn
	*/
    public function testOpen($dummy = 0,  $id = 0)
    {			
		if ($dummy  > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			$entity = $em->getRepository('AcmeZayavkiBundle:Profs')->findEntity($id);
			
			$crawler = $client->request('GET', '/profs_id/'.$entity['id'] );

			$entity['name'] = 'NEW PROF '.rand(0, 10000);
			$crawler = $client->request('POST', '/profs_save/'.(int)$entity['id'], 
							array( 'profs' => $entity) 
						);

			$res = TestHelper::check_result( $client->getResponse()->getContent());	
			$this->assertTrue( $res['result'] == 'true' ); 
			$entity['id'] = $res['id'];
			$var = TestHelper::check_data( $client, '/profs_data',  $entity['id']);	
			
			$this->assertEquals( $entity['name'], $var['item']['name']);
					
			return $entity['id'];	
		}
    }   

	/**
		path:     /profs_delete/nn
	*/
    public function testDelete( $id = 0)
    {	
		if ($id  > 0) {
			$client = static::createClient();
			$crawler = $client->request('GET', '/profs_delete/'.$id );
			$res = TestHelper::check_result( $client->getResponse()->getContent());		
			$this->assertTrue( $res['result'] == 'true' ); 		
			
			$res = TestHelper::check_data( $client, '/profs_data',  $id);
			$this->assertTrue( $res['result'] == 'false' ); // deleted successfully	
		}
    }		
}