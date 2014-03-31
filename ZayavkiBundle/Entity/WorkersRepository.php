<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Workers;
use Doctrine\ORM\EntityRepository;

class WorkersRepository extends EntityRepository
{
	/**
	* Get all Workers defined for specified Tsg
	*
	* @param int $tsgid	
	* @param string $options {A=all, N- none}
	* @param string $output {'easyui','hash','array' - default}		
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findWorkersForTsg($tsgid, $options, $output)
	{
		$query =  $this->getEntityManager()->createQuery(
			'SELECT w.name, w.id, p.name as pname FROM AcmeZayavkiBundle:Workers w, AcmeZayavkiBundle:Profs p where w.deleted=0 and w.ownid =  :id and w.profid = p.id order by w.name'
			);
		$query->setParameter('id', $tsgid);
		$rows = $query->getResult();	
		
		if (substr_count($options, 'N') > 0) {
			array_unshift($rows, array('id' => 0, 'name' => 'НЕТ'));																	  		
		}	
		
		switch ($output) {
			case 'easyui':
					$list = array("total" => count($rows), 
								  "rows"  => $rows);				
					break;
			case 'hash':	
					foreach($rows as $rs) {
						$list[ $rs['id'] ] = $rs['name'];
					}
					break;
			default:		
					$list = $rows;
		}	

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
		$entity = $this->find($id);
		
		if (!$entity) {
			$entity = new Workers();
		}
				
		return array(	'id' 	=> $entity->getId(), 
						'name' 	=> $entity->getName(), 
						'ownid' => $entity->getOwnid(), 
						'profid'=> $entity->getProfid(),
					);
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