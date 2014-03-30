<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Workers;
use Doctrine\ORM\EntityRepository;

class WorkersRepository extends EntityRepository
{

	/**
	* Get list of Workers 
	*
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findWorkersList($tsg, $options)
	{
		$list = array("total" => 0, "rows" => array());
		$query = $this->getEntityManager()->createQuery(
			'SELECT p.id, p.name FROM AcmeZayavkiBundle:Workers p WHERE p.deleted=0 and p.ownid=:tsg ORDER BY p.name ASC'
			);
		$query->setParameter('tsg', $tsg);	
		$res = $query->getResult();
		
		if (substr_count($options, 'N') > 0) {
			array_unshift($res, array('id' => 0, 'name' => 'НЕТ'));																	  		
		}
        return $res;	
	}

	/**
	* Get all Workers defined for specified Tsg
	*
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findWorkersForTsg($id)
	{
	
		$list = array("total" => 0, "rows" => array());
		$query =  $this->getEntityManager()->createQuery(
			'SELECT w.name, w.id, p.name as pname FROM AcmeZayavkiBundle:Workers w, AcmeZayavkiBundle:Profs p where w.deleted=0 and w.ownid =  :id and w.profid = p.id order by w.name'
			);
		$query->setParameter('id', $id);
		$list["rows"] = $query->getResult();	
		$list['total'] = count($list['rows']); 	

        return $list;	
	}
	
	/**
	* Get entity Workets 
	*
	* @param int $id
	* @return array $res
	*/		
	public function findEntity($id)
	{
		$res = $this->find($id);
	    // $query = $this->getEntityManager()->createQuery(
			// 'SELECT p.id, p.name, p.ownid, p.profid FROM AcmeZayavkiBundle:Workers p WHERE p.id = :id'
			// );
		// $query->setParameter('id', $id);
		// $res = current($query->getResult());
		// if (!$res) {
			// $res = array('id' => 0, 'name' => '', 'ownid' => 0, 'profid' => 0);
		// }
				
		return $res;
	}	

	/**
	* Save Workers 
	*
	* @param int $id
	* @param array $var  
	* @return entity id
	*/	
	public function saveEntity( $id, $var)
	{
		$entity = $this->find($id);
		if (!$entity) {
			$entity = new Workers();
		}
			
		$entity->setName(   $var['name'] ); 
		$entity->setProfid( $var['profid']);
		$entity->setOwnid(  $var['ownid'] );
				
		$em = $this->getEntityManager();				
		$em->persist($entity);
		$em->flush();
		$em->clear();
		return $entity->getId();
	}	
	
	
	/**
	* Worker set deleted on
	*
	* @param int $id
	* @return entity id
	*/
	public function deleteEntity($id)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('update Workers set deleted = 1 where id = :id', array('id' => $id));			 
		return $id;
	}
}