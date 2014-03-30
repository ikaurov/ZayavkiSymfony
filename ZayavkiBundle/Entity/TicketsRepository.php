<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Tickets;
use Acme\ZayavkiBundle\Entity\TicketsComment;
use Doctrine\ORM\EntityRepository;
use Acme\ZayavkiBundle\Model\CustomDateFormat;

class TicketsRepository extends EntityRepository
{


	/**
	* Get ticket list
	*
	* @param int $tsg
	* @param int $kv
	* @return array $res
	*/
	public function getTicketList( $param, $env )
	{
	
		$where = "WHERE (t.deleted = 0) AND (t.nr > 0) ".
						(($env['head'] == 0) ? " AND (t.tsgid in (select tsgid from Usertsg where userid=".$env['userid']."))":"").
						(($param['tsg'] > 0) ? " AND (t.tsgid = ".$param['tsg'].")": "" ) ;

        if ( $param['f_categs'] > 0) 	{ 	$where .= ' AND (t.categoryid = '.$param['f_categs'].')'; }
		if ( $param['f_status']  > 0) 	{ 	$where .= ' AND (t.substatusid= '.$param['f_status'].')'; }
		if ( $param['f_closed']  == 0) 	{ 	$where .= ' AND (t.statusid = 1)'; }
		if ( strlen($param['f_poisk']) > 0 ) {
			$where .= ' AND ( (t.nr LIKE "%'.$param['f_poisk'].'%") OR  
							  (t.message LIKE"%'.$param['f_poisk'].'%") OR 
							  (a.objectaddress LIKE "%'.$param['f_poisk'].'%") OR 
							  (a.fio LIKE "%'.$param['f_poisk'].'%") OR 
							  (a.roomnumber LIKE "%'.$param['f_poisk'].'%")
							)'; 
		}
		
		
		switch ($param['predef']) {
			case 1: $where .= ' AND (t.substatusid = 101)';
					break;
			case 2: $where .= ' AND (t.substatusid = 102)';
					break;
			case 3: $where .= ' AND (DATE(NOW()) > DATE(t.dplan)) and (t.statusid = 1)';
					break;		
			case 4: $where .= ' AND (t.alert = 1) and (t.statusid = 1)'; 
					break;
			case 5: $where .= ' AND (DATE(NOW()) = DATE(t.dplan)) and (t.statusid = 1)'; 
					break;						
		}

		// count total records for ticket list
		$stmt = $this->getEntityManager()->getConnection()->prepare( "select count(*) as count from Tickets t LEFT JOIN Accounts a ON a.UserId = t.userid  ".$where );
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$rec = current($rows);
		$list = array("total" => $rec['count'], "rows" => array());
	
		// deprecated field "state" indicated 2 conditions: 1) ($rs['statusid'] == 1) and ($rs['alert']==1) - icon warning
		//                                                  2) ($rs['creator'] == 0)   icone "owner creator"
		// "astate" = 0, 3 when new and no alert?
		// [TODO] move it into the row/cell styler
		
		$query = "SELECT t.id, t.nr, t.alert, t.fopen, '' as state, 'as' as astate, DATE_FORMAT(t.dstart, '%Y-%m-%d %k:%i') as dstartraw, 
						 COALESCE(DATE_FORMAT(t.dstart, '%d.%m.%Y %k:%i'),'') as dstart,
						 COALESCE(DATE_FORMAT(t.dplan, '%d.%m.%Y'),'') as dplan,
						 COALESCE(DATE_FORMAT(dstop, '%d.%m.%Y %k:%i'),'') as dfact,
						 t.statusid, t.message, t.podal, t.note, COALESCE(c.name,'') as cname,
						 COALESCE((select name from Workers where id = t.workerid),'') as wname, 
						 COALESCE((select name from Users where userid = t.dispid),'') as disp, 
						 COALESCE((select name from Comprop where kind=2 and uin = t.substatusid),'') as sname, 
						 COALESCE(DATEDIFF( NOW(), IFNULL(t.dplan,NOW()+10000000)),'') as overdue, 
						 COALESCE((select TSGName from TSGInfo where TSGId = t.tsgid ),'') as tsgname,						 
						 a.Account as account, concat(a.ObjectAddress,'-',a.RoomNumber) as address,
						 a.RoomNumber as kv, a.FIO as fio, COALESCE(a.RegistryMobile,'') as phone, 
						(select count(*) from Users where userid = t.dispid) as creator,t.categoryid					 
				  FROM  Tickets t LEFT JOIN Accounts a ON a.UserId = t.userid, Category c ".$where." AND (c.id = t.categoryid) ORDER BY ".
				  ((strlen($env['sort']) == 0)? "t.nr" : "t.".$env['sort'])." LIMIT ".$env["offset"].",".$env["to"].";";
		
		$stmt = $this->getEntityManager()->getConnection()->prepare( $query );
		$stmt->execute();
		$list["rows"] = $stmt->fetchAll();
// [TODO] Test multiline fields		
		
	//	'message'  => str_replace('[CRLF]',"\n", $rs['message']),
    //  'note'   => str_replace('[CRLF]',"\n", $rs['note']),
		return $list;
	}


	/**
	* Find owner for tsg and flat number. Could be multiple records 
	*
	* @param int $tsg
	* @param int $kv
	* @return array $res
	*/
	public function findOwner($tsg, $kv)
	{	
		$query = $this->getEntityManager()->createQuery(
				"SELECT COALESCE(a.address, CONCAT( CONCAT(a.objectaddress, ','), CONCAT(a.roomtype, a.roomnumber))) as address, a.fio, a.email, a.registrymobile as phone, a.userid, a.account, a.debt
				FROM AcmeZayavkiBundle:Accounts a, AcmeZayavkiBundle:Users u, AcmeZayavkiBundle:Tsginfo t WHERE  a.userid = u.userid and u.tsgcode = t.tsgcode and t.tsgid = :tsg and a.roomnumber = :kv "
			);
		$query->setParameter('tsg', $tsg);	
		$query->setParameter('kv', $kv);
		$res = $query->getResult();
		
        return $res;	
	}


	/**
	* Get entity Ticket 
	*
	* @param int $id
	* @return array $res
	*/		
	public function findEntity( $id, $userid)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Ticket_open(:id, :userid);');
		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':userid', 0);	
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$row  = current($rows);		
		if ($row) {
			$row['message'] = str_replace('[CRLF]',"\n", $row['message']);
			$row['note']    = str_replace('[CRLF]',"\n", $row['note']);
		}
		 
		return $row;
	}	
	
	/**
	* Get history for  Ticket 
	*
	* @param int $id
	* @return array $res
	*/		
	public function getHistory( $id)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Ticket_history(:id);');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$res = $stmt->fetchAll();
	 
		return $res;
	}	


	/**
	* Save Entity 
	*
	* @param Connection $conn
	* @param int $id
	* @param array $var  
	* @return entity array of (id, nr, isnew)
	*/	
	public function saveEntity( $id, $userid, $var)
	{
	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Ticket_upd(:id, :userid, :tsgid, :catid, :msg, :unote, :phone, :email, :ptime,	:podal, :alert, :workid, :dplan, :upduser);');
		 $stmt->bindValue(':id', $id);
		 $stmt->bindValue(':userid',$var['userid']);		
		 $stmt->bindValue(':tsgid', $var['tsgid']);
		 $stmt->bindValue(':catid', $var['categoryid']);
		 $stmt->bindValue(':msg',   $var['message']);
		 $stmt->bindValue(':unote', $var['note']);
		 $stmt->bindValue(':phone', $var['phone']);
		 $stmt->bindValue(':email', $var['email']);
		 $stmt->bindValue(':ptime', $var['preftime']);
		 $stmt->bindValue(':podal', $var['podal']);
		 $stmt->bindValue(':alert', $var['alert']);
		 $stmt->bindValue(':workid',$var['workerid']);
		 $stmt->bindValue(':dplan', $var['dplan']);
		 $stmt->bindValue(':upduser', $userid);		
		
		
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$row  = current($rows);	
	
		return $row;
	}	
	
	/**
	* Changin' ticket state.  
	*
	* @param int $id
	* @param array $var
	* @return array $res
	*/		
	public function setState( $id, $var)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Ticket_close(:id, :state, :lastnote, :userid);');
		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':state', $var['statusid']);
		$stmt->bindValue(':lastnote', $var['comment']);
		$stmt->bindValue(':userid', $var['userid']);	
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$row  = current($rows);			 
		return $row;
	}	

	/**
	* Ticket is set deleted
	*
	* @param $this->getDoctrine()->getEntityManager()->getConnection() $conn
	* @param int $id
	* @return entity id
	*/
	public function deleteEntity($id)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('update Tickets set deleted = 1 where id = :id', array('id' => $id));			 
		return $id;
	}	
	
	/**
	* Get Alerts
	*
	* @param int $userid
	* @return array 
	*/		
	public function getAlerts($userid)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Alerts(:userid);'); //back with 1 row always
		$stmt->bindValue(':userid', $userid);
		$stmt->execute();
		$rows = $stmt->fetchAll();	
		 
		return current($rows);
	}	
	
	/**
	* Get History
	*
	* @param int $userid
	* @param int $id
	* @return array 
	*/		
	public function findHistory($userid, $id)
	{	
		$query = $this->getEntityManager()->createQuery(
				"SELECT t.id, t.nr, t.dstart, t.dstop, t.message, t.statusid FROM AcmeZayavkiBundle:Tickets t WHERE t.userid = :userid and t.id <> :id ORDER BY t.dstart DESC "
			);
		$query->setParameter('userid', $userid);	
		$query->setParameter('id', $id);
		$res = $query->getResult();
		$list = array();
		foreach($res as $rs) {
			$dt1 = (array)$rs['dstart'];
			$dt2 = (array)$rs['dstop'];
			$list[] = array ('id'	    => $rs['id'],
							 'nr' 		=> $rs['nr'],
							 'statusid' => $rs['statusid'],
							 'dstart' 	=> (isset($dt1['date']) ? CustomDateFormat::dateAnsiToRus($dt1['date']) : ''),
							 'dstop' 	=> (isset($dt2['date']) ? CustomDateFormat::dateAnsiToRus($dt2['date']) : ''), 
							 'message' 	=> $rs['message'],
							);
		}
		
        return $list;
	}	
	/**
	* Add comment
	*
	* @param int $id - ticket id	
	* @param int $userid
	* @param string $message
	* @param int $kind
	* @return array 
	*/		
	public function addComment($id, $userid, $message, $kind) {
		
		$entity = new TicketsComment();
			
		$entity->setTicketid( $id );
		$entity->setUserid( $userid );
		$entity->setKind( $kind );
		$entity->setMessage( $message );
				
		$em = $this->getEntityManager();				
		$em->persist($entity);
		$em->flush();
		return $entity->getId();
	}	
}