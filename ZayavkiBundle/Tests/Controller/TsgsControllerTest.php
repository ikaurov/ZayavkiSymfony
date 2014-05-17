<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Entity\Tsgsinfo;

class TsgsControllerTest extends WebTestCase
{
	public function testRepository()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');	
		
		$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('no_head', '', 'easyui', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows['rows']));
		$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('only_head', '', 'easyui', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows['rows']));	
		$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('', '', 'hash', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));		
		$rows = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->getTsgsList('', '', 'array', $kernel->getContainer()->get('transloc')->getTranslated('B'));
		$this->assertTrue(is_array($rows));			
		
	}
	
	/**
		path:     /tsgs
	*/
    public function testIndex()
    {
	    $client = static::createClient();
				
        $crawler = $client->request('GET', '/tsgs');
		$this->assertTrue($crawler->filter('html:contains("tsgs_list")')->count() > 0);	
	}
	/**
		path:     /tsgs_data
	*/	
    public function testData()
    {	
	    $client = static::createClient();
		
        $this->assertTrue( TestHelper::check_data( $client, '/tsgs_data',  -1)['result'] );
    }	
	
    public function testActions()
    {	
	    $client = static::createClient();
		
		$crawler = $client->request('GET', '/tsgs_data');
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertTrue(is_array($res));
		foreach ($res as $rs) {
			$this->testOpen( $rs['id'] );
			$this->testOplist($rs['id']);
			$this->testWorklist($rs['id']);
			
			$this->testSetlk($rs['id'], 0);
			$this->testSetlk($rs['id'], 1);
			$this->testSetlk($rs['id'], $rs['lk']);
		}

    }	
	
	/**
		path:     /tsgs_id/{id}
	*/
    public function testOpen($tsgid = 0)
    {			
		if ($tsgid  > 0) {
			$client = static::createClient();			
			
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
			$entity = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->findEntity($tsgid);
			
			$crawler = $client->request('GET', '/tsgs_id/'.$entity['id'] );
			$this->assertTrue($crawler->filter('html:contains("tsg_users")')->count() > 0);	
					
			return $entity['id'];	
		}
    }	
	
	/**
		path:     /tsgs_oplist/{id}
	*/
    public function testOplist($tsgid = 0)
    {			
		if ($tsgid  > 0) {
			$client = static::createClient();
			$var = TestHelper::check_data( $client, '/tsgs_oplist/'.$tsgid,  -1);	
			$this->assertTrue($var['result']);
		}
    }	
	
	/**
		path:    /tsgs_worklist/{id}
	*/
    public function testWorklist($tsgid = 0)
    {			
		if ($tsgid  > 0) {
			$client = static::createClient();

			$var = TestHelper::check_data( $client, '/tsgs_worklist/'.$tsgid,  -1);	
			$this->assertTrue($var['result']);
		}
    }	
	
	/**
		path:    /tsgs_setlk/{id}/{lk}
	*/
    public function testSetlk($tsgid = 0, $lk = 0)
    {			
		if ($tsgid  > 0) {
		
			$kernel = static::createKernel();
			$kernel->boot();
			$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');	
		
			$client = static::createClient();
			$var = TestHelper::check_data( $client, '/tsgs_setlk/'.$tsgid.'/'.$lk,  -1);	
			$this->assertTrue($var['result']);
			
			$entity = $em->getRepository('AcmeZayavkiBundle:Tsginfo')->findEntity($tsgid);
			$this->assertTrue($lk == $entity['lk']);		
		}
    }	
	
}