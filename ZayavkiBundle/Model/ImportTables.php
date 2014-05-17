<?php
namespace Acme\ZayavkiBundle\Model;

class ImportTables 
{
	private $target;	
	private $source;
	
	public function __construct($target, $source)
	{
		$this->target = $target;
		$this->source = $source;
	}
	
	public function setIdentity($table, $field, $value)
	{
		if ($value == 0) {
			$this->target->exec('ALTER TABLE '.$table.' MODIFY COLUMN '.$field.' INT ');
		} else {
			$this->target->exec('ALTER TABLE '.$table.' MODIFY COLUMN '.$field.' SERIAL ');
		}
	}	
	// TSGINFO
	public function getTsginfo($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = $pdo->query("SELECT * FROM TSGInfo")->fetchAll();
		return $res;
	}
	
	public function countTsginfo($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = current($pdo->query("SELECT count(*) AS cnt FROM TSGInfo")->fetchAll());
		return $res['cnt'];
	}	
	
	public function deleteTsginfo($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$pdo->exec("DELETE FROM TSGInfo");
		return true;
	}	
	
	public function addTsginfo($rs)
	{
		$rs['TSGName']    = $this->encodeTSGName();
		$rs['TSGAddress'] = $this->encodeTSGAddress();
	
	
		$this->target->exec(
			"INSERT INTO `TSGInfo` (TSGId, TSGCode, TSGName, TSGAddress, OpenPeriod, oldDBId, Mode, head, rootid, DateUpdate, DateSetMode, lk) VALUES (".
				(int)$rs['TSGId'].",".
				$this->target->quote($rs['TSGCode']).",".
				$this->target->quote($rs['TSGName']).",".
				$this->target->quote($rs['TSGAddress']).",".
				$this->target->quote($rs['OpenPeriod']).",".
				(int)$rs['oldDBId'].",".
				(int)$rs['Mode'].",".
				(int)$rs['head'].",".
				(int)$rs['rootid'].",".
				$this->target->quote($rs['DateUpdate']).",".
				$this->target->quote($rs['DateSetMode']).",".
				(int)$rs['lk'].");"
		);
		return 1;
	}	
	// COMPROP
	public function getComprop($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _comprop")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM Comprop")->fetchAll();
		}
		return $res;
	}
	
	public function countComprop($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _comprop")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM Comprop")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteComprop($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _comprop");
		} else {
			$res = $this->target->exec("DELETE FROM Comprop");
		}
		return true;
	}	
	
	public function addComprop($rs)
	{
		$rs['name'] = $this->encodeTranslit($rs['name']);
	
		$this->target->exec(
			"INSERT INTO `Comprop` (id, name, uin, kind) VALUES (".
				(int)$rs['id'].",".
				$this->target->quote($rs['name']).",".
				(int)$rs['uin'].",".
				(int)$rs['kind'].");"
		);
		return 1;
	}		
	
	// PROF
	public function getProfs($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _profs")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM Profs")->fetchAll();
		}
		return $res;
	}
	
	public function countProfs($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _profs")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM Profs")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteProfs($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _profs");
		} else {
			$res = $this->target->exec("DELETE FROM Profs");
		}
		return true;
	}	
	
	public function addProf($rs)
	{
		$rs['name'] = $this->encodeTranslit($rs['name']);
			
		$this->target->exec(
			"INSERT INTO `Profs` (id, name, deleted) VALUES (".
				(int)$rs['id'].",".
				$this->target->quote($rs['name']).",".
				(int)$rs['deleted'].");"
		);
		return 1;
	}	

	// Category
	public function getCategory($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _categs")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM Category")->fetchAll();
		}
		return $res;
	}
	
	public function countCategory($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _categs")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM Category")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteCategory($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _categs");
		} else {
			$res = $this->target->exec("DELETE FROM Category");
		}
		return true;
	}	
	
	public function addCategory($rs)
	{
	
		$rs['name'] = $this->encodeTranslit($rs['name']);
			
		$this->target->exec(
			"INSERT INTO `Category` (id, name, parentid, deleted) VALUES (".
				(int)$rs['id'].",".
				$this->target->quote($rs['name']).",".
				(int)$rs['_parentId'].",".
				(int)$rs['deleted'].");"
		);
		return 1;
	}		

	// Workers
	public function getWorkers($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _workers")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM Workers")->fetchAll();
		}
		return $res;
	}
	
	public function countWorkers($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _workers")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM Workers")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteWorkers($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _workers");
		} else {
			$res = $this->target->exec("DELETE FROM Workers");
		}
		return true;
	}	
	
	public function addWorkers($rs)
	{
		$rs['name'] = $this->encodeTranslit($rs['name']);
			
		$this->target->exec(
			"INSERT INTO `Workers` (id, name, ownid, profid, deleted) VALUES (".
				(int)$rs['id'].",".
				$this->target->quote($rs['name']).",".
				(int)$rs['ownid'].",".
				(int)$rs['profid'].",".
				(int)$rs['deleted'].");"
		);
		return 1;
	}				
	// USERS
	public function getUsers($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = $pdo->query("SELECT * FROM Users")->fetchAll();
		return $res;
	}
	
	public function countUsers($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = current($pdo->query("SELECT count(*) AS cnt FROM Users")->fetchAll());
		return $res['cnt'];
	}	
	
	public function deleteUsers($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$pdo->exec("DELETE FROM Users");
		return true;
	}	
	
	public function addUsers($rs)
	{
		$rs['UserName'] = $this->encodeTranslit($rs['UserName']);
	
		$this->target->exec(
			"INSERT INTO `Users` (UserId, TSGCode, UserName, Password, isBlocked, Type, isReset, NewPassword, failedCount, FrozenDate, WaitRegistry) VALUES (".
				(int)$rs['UserId'].",".
				$this->target->quote($rs['TSGCode']).",".
				$this->target->quote($rs['UserName']).",".
				$this->target->quote($rs['Password']).",".
				(int)$rs['isBlocked'].",".
				(int)$rs['Type'].",".
				(int)$rs['isReset'].",".
				$this->target->quote($rs['NewPassword']).",".				
				(int)$rs['failedCount'].",".
				$this->target->quote($rs['FrozenDate']).",".
				(int)$rs['WaitRegistry'].");"
		);
		return 1;
	}	

	// Accounts
	public function getAccounts($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = $pdo->query("SELECT * FROM Accounts")->fetchAll();
		return $res;
	}
	
	public function countAccounts($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$res = current($pdo->query("SELECT count(*) AS cnt FROM Accounts")->fetchAll());
		return $res['cnt'];
	}	
	
	public function deleteAccounts($db)
	{
		$pdo = ($db == 'target') ? $this->target : $this->source;
		$pdo->exec("DELETE FROM Accounts");
		return true;
	}	
	
	public function addAccounts($rs)
	{
		$rs['UserName'] = $this->encodeTranslit($rs['UserName']);
		
		$rs['Question'] = '';
		$rs['Answer']   = '';
		$rs['Address']  = '';	
		$rs['ObjectAddress']  = '';
		$rs['FIO']      = $this->encodeTranslit($rs['FIO']);
		$rs['Email']    = 'person@example.com';
		$rs['RegistryFIO']    = $rs['FIO'];
		$rs['OwnerFIO']       = $rs['FIO'];
		$rs['RegistryEmail']  = 'person@example.com';			
		$rs['RegistryMobile'] = '911 000 2334';
		$rs['Mobile']         = '911 000 2334';
		$rs['Phones']         = '911 000 2334';				
	
	
		$this->target->exec(
			"INSERT INTO `Accounts` (AccountId, UserId , Account, DisplayNumber, Question, Answer, Address, AddressId, ObjectAddress, 
						RoomType, RoomNumber, DoubleCntr, FIO, Email, Debt, isUpdated, RegistryFIO,OwnerFIO, RegistryEmail, RegistrySquare, 
						RegistryMobile, id, DateRegistry, Square, Mobile, Phones, DontUpdate) VALUES (".
				(int)$rs['AccountId'].",".
				(int)$rs['UserId'].",".
				$this->target->quote($rs['Account']).",".
				$this->target->quote($rs['DisplayNumber']).",".
				$this->target->quote($rs['Question']).",".
				$this->target->quote($rs['Answer']).",".
				$this->target->quote($rs['Address']).",".	
				(int)$rs['AddressId'].",".
				$this->target->quote($rs['ObjectAddress']).",".
				$this->target->quote($rs['RoomType']).",".
				$this->target->quote($rs['RoomNumber']).",".
				(int)$rs['DoubleCntr'].",".
				$this->target->quote($rs['FIO']).",".
				$this->target->quote($rs['Email']).",".				
				(double)$rs['Debt'].",".
				(int)$rs['isUpdated'].",".
				$this->target->quote($rs['RegistryFIO']).",".
				$this->target->quote($rs['OwnerFIO']).",".
				$this->target->quote($rs['RegistryEmail']).",".				
				(double)$rs['RegistrySquare'].",".
				$this->target->quote($rs['RegistryMobile']).",".
				$this->target->quote($rs['id']).",".
				$this->target->quote($rs['DateRegistry']).",".
				(double)$rs['Square'].",".
				$this->target->quote($rs['Mobile']).",".
				$this->target->quote($rs['Phones']).",".					
				(int)$rs['DontUpdate'].");"
		);
		return 1;
	}		
	
	// User
	public function getUser($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _users")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM User")->fetchAll();
		}
		return $res;
	}
	
	public function getTsgForUser($id)
	{
		$res = $this->source->query("SELECT * FROM _usertsg WHERE userid = ".$id)->fetchAll();
		return $res;
	}	
	
	public function deleteTsgForUser()
	{
		$res = $this->target->exec("DELETE FROM Usertsg ");
		return true;
	}		
	
	public function countUser($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _users")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM User")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteUser($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _users");
		} else {
			$res = $this->target->exec("DELETE FROM User");
		}
		return true;
	}	
	
	public function addUser($rs, $tsgs)
	{
		$this->target->exec(
			"INSERT INTO `User` (id, username, password, name, deleted) VALUES (".
				(int)$rs['userid'].",".
				$this->target->quote($rs['login']).",".
				$this->target->quote($rs['pwd']).",".
				$this->target->quote($rs['name']).",".
				(int)$rs['deleted'].");"
		);
		
		 foreach( $tsgs as $rec) {
			 $this->target->exec(
				 "INSERT INTO `Usertsg` (userid, tsgid) VALUES (".
					 (int)$rs['userid'].",".
					 (int)$rec['tsgid'].");"
			 );		
		 }
		return 1;
	}		 

	// Tickets
	public function getTickets($db)
	{
		if ($db == 'source') {
			$res = $this->source->query("SELECT * FROM _tickets")->fetchAll();
		} else {
			$res = $this->target->query("SELECT * FROM Tickets")->fetchAll();
		}
		return $res;
	}
	
	public function getTicketComments($id)
	{
		$res = $this->source->query("SELECT * FROM _tickets_comment WHERE ticketid = ".$id)->fetchAll();
		return $res;
	}	
	
	public function countTickets($db)
	{
		if ($db == 'source') {
			$res = current($this->source->query("SELECT count(*) AS cnt FROM _tickets")->fetchAll());
		} else {
			$res = current($this->target->query("SELECT count(*) AS cnt FROM Tickets")->fetchAll());
		}
		return $res['cnt'];
	}	
	
	public function deleteTickets($db)
	{
		if ($db == 'source') {
			$res = $this->source->exec("DELETE FROM _tickets");
		} else {
			$res = $this->target->exec("DELETE FROM Tickets");
		}
		return true;
	}	
	
	public function deleteComments()
	{
		$res = $this->target->exec("DELETE FROM Tickets_comment ");
		return true;
	}		
	
	public function addTickets($rs, $comms)
	{
		
		$this->target->exec(
			"INSERT INTO `Tickets` (  id, nr, alert, statusid, substatusid, categoryid, email, phone, preftime, userid, tsgcode,
									  dstart, dstop,dwork,dplan,workerid,usernote,message,tsgid, dispid,lastnote,address,note,
									  fopen,stopid,rootid,podal, deleted) VALUES (".
				(int)$rs['ticketid'].",". 
				(int)$rs['nr'].",". 
				(int)$rs['alert'].",".
 				(int)$rs['statusid'].",".
 				(int)$rs['substatusid'].",". 
 				(int)$rs['categoryid'].",".
 				$this->target->quote($rs['email']).",". 
				$this->target->quote($rs['phone']).",". 
 				$this->target->quote($rs['preftime']).",". 
 				(int)$rs['userid'].",". 
				$this->target->quote($rs['tsgcode']).",".
				$this->target->quote($rs['dstart']).",". 
				$this->target->quote($rs['dstop']).",".
 				$this->target->quote($rs['dwork']).",".
				$this->target->quote($rs['dplan']).",".
 				(int)$rs['workerid'].",".
 				$this->target->quote($rs['usernote']).",".
				$this->target->quote($rs['message']).",".
 				(int)$rs['tsgid'].",".
 				(int)$rs['dispid'].",".
 				$this->target->quote($rs['lastnote']).",".
 				$this->target->quote($rs['address']).",".
 				$this->target->quote($rs['note']).",". 
 				(int)$rs['fopen'].",".
  				(int)$rs['stopid'].",".
 				(int)$rs['rootid'].",".
 				$this->target->quote($rs['podal']).",". 
 				(int)$rs['deleted'].");"
		);
		
		 foreach( $comms as $rec) {
			 $this->target->exec(
				 "INSERT INTO `Tickets_comment` (ticketid, message, dcreate, userid, kind) VALUES (".
					 (int)$rs['ticketid'].",".
					 $this->target->quote($rec['message']).",".
					 $this->target->quote($rec['dcreate']).",".
					 (int)$rec['userid'].",".
					 (int)$rec['kind'].");"
			 );		
		 }
		return 1;
	}			

	public function finalizeTickets()
	{	
		$this->target->exec("UPDATE `Tickets` set dstart = NULL WHERE dstart='00.00.0000'");
		$this->target->exec("UPDATE `Tickets` set dplan = NULL WHERE dplan='00.00.0000'");
		$this->target->exec("UPDATE `Tickets` set dwork = NULL WHERE dwork='00.00.0000'");
		$this->target->exec("UPDATE `Tickets` set dstop = NULL WHERE dstop='00.00.0000'");	    
		$this->target->exec("UPDATE Tickets INNER JOIN Accounts ON Tickets.userid = Accounts.UserId SET Tickets.address = CONCAT(Accounts.ObjectAddress, ',', Accounts.RoomType,Accounts.RoomNumber)");
	}
	// Encoding part
	public function encodeTSGName()
	{
		$names = array('Workers','Cleaners','Profs','Construction','Masters','Supply','Maintenance','Super','Ace','Perfect');
		$suf   = array('AS','LTD','INC','OOO','ZAO','AB','GMBH','FGUP','GP','FIN');
		$pref  = array('Red','Blue','Green','Gray','White','Black','Navy','Orange','Magenta','Transparent');		
	
		return $pref[ rand( 0 , 9 )].' '.$name[ rand( 0 , 9 )].' '.$suf[ rand( 0 , 9 )];
	}
	
	public function encodeTSGAddress()
	{
		$names = array('Berlin','Bohn','Dresden','Bayern','Burnberg','Koln','Rostok','Lubek','Essen','Frankfurt');
		$pref  = array('Red','Blue','Green','Gray','White','Black','Navy','Orange','Magenta','Transparent');		
	
		return $pref[ rand( 0 , 9 )].' '.$name[ rand( 0 , 9 )].' str. '.rand( 1 , 152 );
	}
	
	public function encodeTranslit($value)
	{	
		return str_replace('ÀÁÂÃÄÅ¨ÆÇÈÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÛÚÜİŞß', 'ABVGDEEJZIKLMNOPRSTUFHZCSSY``EUY', mb_strtoupper($value));	
	}	

	public function getAddressForUser($userid)
	{	
		$res = $this->source->query("SELECT t.TSGAddress FROM Users u, TSGInfo t WHERE u.TSGCode = t.TSGCode and u.UserId = ".$userid)->fetchAll();
		$res = current($res);
		return $res['TSGAddress'];
	}	
		
}
