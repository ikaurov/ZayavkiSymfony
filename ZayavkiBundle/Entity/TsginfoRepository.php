<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Tsginfo;
use Doctrine\ORM\EntityRepository;

class TsginfoRepository extends EntityRepository
{
	/**
	* Get organizations for specified conditions. 
	*
	* @param string $kind {'only_head','no_head','all' - default}
	* @param string $options 'N' is for "Not" option
	* @param string $output {'easyui','hash','array' - default}
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function getTsgsList($kind, $options, $output)
	{
		$qb = $this->getEntityManager()->createQueryBuilder()
			->from('AcmeZayavkiBundle:Tsginfo', 't')
			->select("t.tsgname as name, t.tsgid as id, t.head, t.lk")
			->orderBy('t.head','DESC')
			->addOrderBy('t.tsgname');
			
		if ($kind == 'only_head') {
			$qb->where("t.head = 1");		
		}
		if ($kind == 'no_head') {
			$qb->where("t.head = 0");		
		}			
		
		$query = $qb->getQuery();
		$res = $query->getResult();
		
		if (substr_count($options, 'N') > 0) {
			array_unshift($res, array('id' => 0, 'name' => 'НЕТ', 'head' => 0, 'lk' => 0));																	  		
		}
		if (substr_count($options, 'A') > 0) {
			array_unshift($res, array('id' => -1, 'name' => 'ВСЕ', 'head' => 0, 'lk' => 0));																	  		
		}		
		
		switch ($output) {
			case 'easyui': $list = array("total" => count($res), 
										 "rows"  => $res);				
					break;
			case 'hash':
					foreach($res as $rs) {
						$list[ $rs['id'] ] = $rs['name'];
					}
					break;
			default:
					$list = $res;
		}
		return $list;
	}
	
	/**
	* Get all Tsgs. head=1 is on the top 
	*
	* @param int $id - operator id
	* @param string $output {'easyui','hash','array' - default}	
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findTsgsListForUser($id, $output)
	{
		$query = $this->getEntityManager()->createQuery(
			'SELECT p.tsgid as id, p.tsgname as name, p.head as head FROM AcmeZayavkiBundle:Tsginfo p, AcmeZayavkiBundle:Usertsg u 
			 WHERE p.tsgid = u.tsgid and u.userid = :userid
			 ORDER BY p.head desc, p.tsgname ASC'
			);
		$query->setParameter('userid', $id);	
		$res = $query->getResult();
		
		switch ($output) {
			case 'easyui': $list = array("total" => count($res), 
										 "rows"  => $res);				
					break;
			case 'hash':
					foreach($res as $rs) {
						$list[ $rs['id'] ] = $rs['name'];
					}
					break;
			default:
					$list = $res;
		}
		return $list;		
	}
	
	/**
	* Get all Tsgs for user. head=1 is on the top 
	*
	* @param int $id - operator id
	* @return array $list: Hash
	*/
	public function findTsgsListForUserHash($id)
	{	
		// $query = $this->getEntityManager()->createQuery(
			// 'SELECT p.tsgid as id, p.tsgname as name, p.head as head FROM AcmeZayavkiBundle:Tsginfo p, AcmeZayavkiBundle:Usertsg u 
			// WHERE p.tsgid = u.tsgid and u.userid = :userid
			// ORDER BY p.head desc, p.tsgname ASC'
			// );
		// $query->setParameter('userid', $id);	
		// $res = $query->getResult();
		// $tsgs = array();		
		// foreach ($res as $row) {
			// $tsgs[ $row['id'] ] = $row['name'];			
		// }		
        // return $tsgs;
			return $this->findTsgsListForUser($id, 'hash');
	}	
	
	/**
	* Get entity TsgInfo 
	*
	* @param int $id
	* @return array $res
	*/
	public function findEntity($id)
	{	
		$entity = $this->find($id);
		
		if (!$entity) {
			$entity = new Tsginfo();
		}
		
		return array('id' 	   => $entity->getTsgid(), 
					 'tsgname' => $entity->getTsgname(), 
					 'tsgcode' => $entity->getTsgcode(), 
					 'lk' 	   => $entity->getLk()
					 );
	}	
	
	/**
	* Set LK for TsgInfo 
	*
	* @param int $id
	* @param int lk = {0, 1}
	*/
	public function setLkforTsg($id, $lk)
	{	
		$connection = $this->getEntityManager()->getConnection()
							->executeUpdate('update TSGInfo set lk = :lk where TSGId = :id', array( 'id' => $id, 
																									'lk' => $lk ));	
	}	
	/**
	* Return Id of Head tsg. 0 if not found
	*
	* @return int $id
	*/
	public function getTsgHeadId()
	{
	    $res = current($this->getTsgsList('only_head', '', 'array'));	
		return ($res)? (int)$res['id']: 0 ;
	}
	
	public function deleteAllEntity()
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('delete from TSGInfo');			 
	}	
		
}