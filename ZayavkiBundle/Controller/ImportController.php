<?php

namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Model\CustomDateFormat;
use Acme\ZayavkiBundle\Model\Db;
use Acme\ZayavkiBundle\Model\ImportTables;


class ImportController extends Controller
{

	public function indexAction() 
	{
		$info = array();
		$import = new ImportTables( $this->container->get('main_database')->getPDO(),	
									$this->container->get('importsource')->getPDO() );
									
									
	//	$info[] = array( 'name' => $import->encodeTSGName() );	
	//	$info[] = array( 'name' => $import->encodeTSGAddress() );	
	
//	$var = str_replace(array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ы','Ъ','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'), 
//					   array('A','B','V','G','D','E','E','J','Z','I','K','L','M','N','O','P','R','S','T','U','F','H','Z','C','S','S','Y','`','`','E','U','Y','a','b','v','g','d','e','e','j','z','i','k','l','m','n','o','p','r','s','t','u','f','h','z','c','s','s','y','`','`','e','u','y'),
//					   'Юдин');
	
	
		$info[] = array( 'name' => $this->loadUsers($import) );									
		$info[] = array( 'name' => $this->loadTsginfo($import) );
		$info[] = array( 'name' => $this->loadAccounts($import) );		
		$info[] = array( 'name' => $this->loadComprop($import) );
		$info[] = array( 'name' => $this->loadProfs($import) );
		$info[] = array( 'name' => $this->loadCategory($import) );
		$info[] = array( 'name' => $this->loadWorkers($import) );
		$info[] = array( 'name' => $this->loadUser($import) );
		$info[] = array( 'name' => $this->loadTickets($import) );

	
		return $this->render('AcmeZayavkiBundle:Import:index.html.twig', array('info' => $info));
		
	}

	public function loadUsers($import) 
	{
		$res = $import->getUsers('source');
		$protokol = 'Users: Отправленно/Получено '.count($res).'/';
		$import->deleteUsers('target');
		foreach ($res as $rs) {
			$import->addUsers($rs);
		}
		return $protokol.$import->countUsers('target');	
	}
	
	public function loadTsginfo($import) 
	{
		$res = $import->getTsginfo('source');
		$protokol = 'TSGInfo: Отправленно/Получено '.count($res).'/';
		$import->deleteTsginfo('target');
		foreach ($res as $rs) {
			$import->addTsginfo($rs);
		}
		return $protokol.$import->countTsginfo('target');	
	}

	public function loadAccounts($import) 
	{
		$res = $import->getAccounts('source');
		$protokol = 'Accounts: Отправленно/Получено '.count($res).'/';
		$import->deleteAccounts('target');
		foreach ($res as $rs) {
			$import->addAccounts($rs);
		}
		return $protokol.$import->countAccounts('target');	
	}
	
	public function loadComprop($import) 
	{	
		$res = $import->getComprop('source');
		$protokol = 'Comprop: Отправленно/Получено '.count($res).'/';
		$import->deleteComprop('target');
		$import->setIdentity('Comprop', 'id', 0);
		foreach ($res as $rs) {
			$import->addComprop($rs);
		}
		$import->setIdentity('Comprop', 'id', 1);
		return $protokol.$import->countComprop('target');	
	}
	
	public function loadProfs($import) 
	{
		$res = $import->getProfs('source');
		$protokol = 'Profs: Отправленно/Получено '.count($res).'/';
		$import->deleteProfs('target');
		$import->setIdentity('Profs', 'id', 0);
		foreach ($res as $rs) {
			$import->addProf($rs);
		}
		$import->setIdentity('Profs', 'id', 1);
		return $protokol.$import->countProfs('target');	
	}
	
	public function loadCategory($import) 
	{
		$res = $import->getCategory('source');
		$protokol = 'Category: Отправленно/Получено '.count($res).'/';
		$import->deleteCategory('target');
		$import->setIdentity('Category', 'id', 0);
		foreach ($res as $rs) {
			$import->addCategory($rs);
		}
		$import->setIdentity('Category', 'id', 1);
		return $protokol.$import->countCategory('target');	
	}	
	
	public function loadWorkers($import) 
	{
		$res = $import->getWorkers('source');
		$protokol = 'Workers: Отправленно/Получено '.count($res).'/';
		$import->deleteWorkers('target');
		$import->setIdentity('Workers', 'id', 0);
		foreach ($res as $rs) {
			$import->addWorkers($rs);
		}
		$import->setIdentity('Workers', 'id', 1);
		return $protokol.$import->countWorkers('target');	
	}	
	
	public function loadUser($import) 
	{
		$res = $import->getUser('source');
		$protokol = 'User: Отправленно/Получено '.count($res).'/';
		$import->deleteTsgForUser();		
		$import->deleteUser('target');
		$import->setIdentity('User', 'id', 0);
		foreach ($res as $rs) {
			$import->addUser($rs, $import->getTsgForUser($rs['userid']));
		}
		$import->setIdentity('User', 'id', 1);
		return $protokol.$import->countUser('target');	
	}	

	public function loadTickets($import) 
	{
		$res = $import->getTickets('source');
		$protokol = 'Tickets: Отправленно/Получено '.count($res).'/';
		$import->deleteComments();
		$import->deleteTickets('target');		
		$import->setIdentity('Tickets', 'id', 0);
		foreach ($res as $rs) {
			$import->addTickets($rs, $import->getTicketComments($rs['ticketid']));
		}
		$import->finalizeTickets();	
		
		$import->setIdentity('Tickets', 'id', 1);
		return $protokol.$import->countTickets('target');	
	}	
}

