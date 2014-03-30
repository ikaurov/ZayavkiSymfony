<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Profs;
use Doctrine\ORM\EntityRepository;

class ProfsRepository extends EntityRepository
{

	/**
	* Get Profs list
	* @param string $output {'easyui','hash','array' - default}	
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findProfsList( $options, $output )
	{
		$list = array();
		$rows = $this->getEntityManager()->createQuery(
			'SELECT p.id, p.name FROM AcmeZayavkiBundle:Profs p WHERE p.deleted=0 ORDER BY p.name ASC'
			)->getResult();
		
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
	* Get entity Profs 
	*
	* @param int $id
	* @return array $res
	*/		
	public function findEntity($id)
	{	
		$res = $this->find($id);	 
		return $res;
	}	
	/**
	* Save Profs 
	*
	* @param int $id
	* @param array $var  
	* @return entity id
	*/	
	public function saveEntity( $id, $var)
	{
		$entity = $this->find($id);	
		if (!$entity) {
			$entity = new Profs();
		}
			
		$entity->setName( $var['name'] );
				
		$em = $this->getEntityManager();				
		$em->persist($entity);
		$em->flush();
		$em->clear();
		return $entity->getId();
	}
	
	/**
	* Profs set deleted on
	*
	* @param int $id
	* @return entity id
	*/
	public function deleteEntity( $id)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('update Profs set deleted = 1 where id = :id', array('id' => $id));			 
		return $id;
	}
}