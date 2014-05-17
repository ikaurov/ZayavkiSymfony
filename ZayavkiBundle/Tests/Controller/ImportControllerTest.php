<?php
namespace Acme\ZayavkiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\ZayavkiBundle\Tests\Model\TestHelper;
use Acme\ZayavkiBundle\Model\ImportTables;

class ImportControllerTest extends WebTestCase
{
	public function testGetAddress()
	{
		$kernel = static::createKernel();
		$kernel->boot();
		$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

		$import = new ImportTables();

		$address = $import->getAddressForUser(1750);		
		$this->assertTrue(strlen($address) > 0);

	}
	
	public function encodeTSGName()
	{
		$import = new ImportTables();
		echo $import->encodeTSGName();	
	}

	public function encodeTSGAddress()
	{
		$import = new ImportTables();
		echo $import->encodeTSGAddress();
	}


}

