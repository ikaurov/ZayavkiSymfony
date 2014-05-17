<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\Category;

class CategoryControllerTest extends WebTestCase
{
	
	public function testRepository()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');		
			
		$rows = $em->getRepository('AcmeZayavkiBundle:Category')->findCategoryTree();
		$this->assertTrue(is_array($rows['rows']));
		$rows = $em->getRepository('AcmeZayavkiBundle:Category')->findGroupsOrderedByName('', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));
		$rows = $em->getRepository('AcmeZayavkiBundle:Category')->findCategoryListHash('', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));

	}
	
	/**
		path:     /categories
	*/
    public function testIndex()
    {
	    $client = static::createClient();
				
        $crawler = $client->request('GET', '/categories');
		$this->assertTrue($crawler->filter('html:contains("categs_list")')->count() > 0);	
	}
	/**
		path:     /categories_data
	*/
    public function testData()
    {	
	    $client = static::createClient();
        $this->assertTrue( TestHelper::check_data( $client, '/categories_data',  -1)['result'] );
    }
	
    public function testActions()
    {	
	
		$id  = $this->testOpen(1, 0, 0); // main
		$id2 = $this->testOpen(1, 0, $id);
		$id = $this->testOpen(1, $id2, $id); // update
		$this->testDelete( $id );	
		$this->testDelete( $id2 );
    }	
	
	/**
		path:     /categories_id/nn
	*/
    public function testOpen($dummy = 0,  $id = 0, $parent = 0)
    {			
		if ($dummy  > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			$entity = $em->getRepository('AcmeZayavkiBundle:Category')->findEntity($id);
			
			$crawler = $client->request('GET', '/categories_id/'.$entity['id'] );

			$entity['name'] = 'NEW CAT '.rand(0, 10000);
			$entity['parentid'] = $parent;
			$crawler = $client->request('POST', '/categories_save/'.(int)$entity['id'], 
							array( 'category' => $entity) 
						);

			$res = TestHelper::check_result( $client->getResponse()->getContent());	
			$this->assertTrue( $res['result'] == 'true' ); 
			$entity['id'] = $res['id'];
			$var = TestHelper::check_data( $client, '/categories_data',  $entity['id']);	
			
			$this->assertEquals( $entity['name'], $var['item']['name']);
					
			return $entity['id'];	
		}
    }   

	/**
		path:     /categories_delete/nn
	*/
    public function testDelete( $id = 0)
    {	
		if ($id  > 0) {
			$client = static::createClient();
			$crawler = $client->request('GET', '/categories_delete/'.$id );
			$res = TestHelper::check_result( $client->getResponse()->getContent());		
			$this->assertTrue( $res['result'] == 'true' ); 		
			
			$res = TestHelper::check_data( $client, '/categories_data',  $id);
			$this->assertTrue( $res['result'] == 'false' ); // deleted successfully	
		}
    }	
	
}