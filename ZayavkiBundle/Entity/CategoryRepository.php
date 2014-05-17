<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
	/**
	* Get Catedories as tree 
	*
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findCategoryTree()
	{
		$list = array("total" => 0, "rows" => array());
		$rows = $this->getEntityManager()->createQuery(
			'SELECT p.id, p.name, p.parentid FROM AcmeZayavkiBundle:Category p WHERE p.deleted=0 ORDER BY p.name ASC'
			)->getResult();
			
		$list["total"] = count($rows); 
		foreach ($rows as $rec) {
			$el = array('id' => $rec['id'], 'name' => $rec['name']);
       		if (  $rec['parentid'] > 0) {
        			$el['_parentId'] = $rec['parentid'];
       		}			
			$list["rows"][] = $el;
		}
        return $list;	
	}
	/**
	* Get all top level Categories 
	*
	* @param string $option
	* @return array $res: [1] => value
	*/
	public function findGroupsOrderedByName($option = '', $translate)
	{	
	    $rows = $this->getEntityManager()->createQuery(
			'SELECT p.id, p.name FROM AcmeZayavkiBundle:Category p WHERE p.deleted=0 and p.parentid=0 ORDER BY p.name ASC'
			)->getResult();
		 
		$res = array();
		if (substr_count($option, 'N') > 0) {
			$res = array(0 => $translate['basic.none']);																				  		
		}
		foreach ($rows as $row) {
			$res[ $row['id'] ] = $row['name'];
		}
		return $res;
	}	
	
	/**
	* Get all Categories hierarchically
	*
	* @param string $option {N}
	* @return hash array $res
	*/
	public function findCategoryListHash( $option = '', $translate)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Category_tree()');
		$stmt->execute();
		$rows = $stmt->fetchAll();	
				 
		$res = array();
		if (substr_count($option, 'N') > 0) {
			$res[ 0 ] = $translate['basic.none'];																				  		
		}
		foreach ($rows as $row) {
			$res[ $row['id'] ] = $row['name'];
		}
		return $res;
	}	

	/**
	* Get entity Category 
	*
	* @param int $id
	* @return entity $entity
	*/		
	public function findEntity($id)
	{
		$entity = $this->find($id);	
		if (!$entity) {
			$entity = new Category(); 
		}
		
		return array('id' 		=> (int)$entity->getId(), 
					 'name' 	=> $entity->getName(), 
					 'parentid'	=> $entity->getParentid(), 
					);
	}	
	/**
	* Save Category 
	*
	* @param int $id
	* @param array $var  
	* @return entity id
	*/	
	public function saveEntity($id, $var)
	{
		$entity = $this->find($id);	
		if (!$entity) {
			$entity = new Category();
		}
			
		$entity->setName( $var['name'] );
		$entity->setParentid( $var['parentid']);
				
		$em = $this->getEntityManager();				
		$em->persist($entity);
		$em->flush();
		$em->clear();
		return $entity->getId();
	}
	
	/**
	* Category set deleted on
	*
	* @param int $id
	* @return entity id
	*/
	public function deleteEntity( $id)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('update Category set deleted = 1 where id = :id', array('id' => $id));			 
		return $id;
	}	
}