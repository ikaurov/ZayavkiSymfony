<?php
namespace Acme\ZayavkiBundle\Entity;
use Acme\ZayavkiBundle\Entity\Comprop;
use Doctrine\ORM\EntityRepository;

class CompropRepository extends EntityRepository
{

/**
*
*
*/
	public function findEntityByUin($kind, $uin) 
	{
		$entity = $repository->findOneBy(
						array('kind' => $kind, 'uin' => $uin)
					);
		
		if ($entity) {
			$entity = new Comprop();
		}
		$res = array('id'   => $entity->getId(),
					 'name' => $entity->getName(),
					 'uin'  => $entity->getUin()
					 );			
		
		// $query = $this->getEntityManager()->createQuery(
			// 'SELECT c.id, c.name, c.uin FROM AcmeZayavkiBundle:Comprop c WHERE c.kind = :kind and c.uin=:uin'
			// );
		// $query->setParameter('kind', $kind);
		// $query->setParameter('uin', $uin);
		// $res = current($rows = $query->getResult());
		// if (!$res) {
			// $res = array('id' => 0,
						 // 'name' => '',
						 // 'uin' => 0);
		// }
		return $res;
	}
/**
*
*
*/
	public function findEntityById($kind, $id) 
	{
		$entity = $repository->findOneBy(
						array('kind' => $kind, 'id' => $id)
					);
		
		if ($entity) {
			$entity = new Comprop();
		}
		$res = array('id'   => $entity->getId(),
					 'name' => $entity->getName(),
					 'uin'  => $entity->getUin()
					 );	

		return $res;
	}	
/**
*
*
*/
	public function findCompropListHash($kind, $options)
	{
		$query = $this->getEntityManager()->createQuery(
			'SELECT c.id, c.name, c.uin FROM AcmeZayavkiBundle:Comprop c WHERE c.kind = :kind ORDER BY c.uin ASC'
			);
		$query->setParameter('kind', $kind);
		$rows = $query->getResult();
	
		$list = array();
		if (substr_count($options, 'N') > 0) {
			$list[ 0 ] = 'НЕТ';																				  		
		}
		
		foreach ($rows as $row) {
			$list[$row['uin']] = $row['name'];
		}
		return $list;
		
	}	
}