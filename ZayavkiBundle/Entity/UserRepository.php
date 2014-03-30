<?php
namespace Acme\ZayavkiBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
	
		$query = $this->getEntityManager()->createQuery(
			'SELECT u.id FROM AcmeZayavkiBundle:User u WHERE u.username = :username '
			);
		$query->setParameter('username', $username);		
		$res = current($query->getResult());
		$id = ($res) ? $res['id'] : 0;
		$user = $this->find($id);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
	
	
	/**
	* List of users as a tree 
	*
	* @param Connection conn
	* @param int $tsg
	* @return array $list
	*/
	public function findUsersTree( $tsg)
	{

		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Users_tree(:tsg, 1 );');
		$stmt->bindValue('tsg', $tsg);	
		$stmt->execute();
		$rows = $stmt->fetchAll();		
		
		$list = array();
		foreach ($rows as $rs) {
    	   	    $el = array('id'      => (($rs['title'] == 1)? 'A'.$rs['id']: $rs['id']), 
							'name'    => $rs['name'], 
							'company' => $rs['title'], // 1 is for tsg, 2 is for user
							'state'   => 'open'
							);
        		if (  $rs['parentid'] > 0) {
        			$el['_parentId'] = 'A'.$rs['parentid'];
        			$el['state']     = 'open';
        		}
	       		$list[] = $el;				
		}		
        return $list;		
	}
	
	
	public function cntHeadUser($id)
	{
		$query = $this->getEntityManager()->createQuery(
			'SELECT t.head FROM AcmeZayavkiBundle:Tsginfo t, AcmeZayavkiBundle:Usertsg u WHERE t.tsgid = u.tsgid and u.userid = :id and t.head = 1 '
			);
		$query->setParameter('id', $id);		
		return count($query->getResult());
	}	

	/**
	* List of users as a list
	*
	* @param int $tsg
	* @return array $list
	*/
	public function findUsersList($tsg)
	{
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Users_tree(:tsg, 2 );');
		$stmt->bindValue('tsg', $tsg);	
		$stmt->execute();
		$rows = $stmt->fetchAll();	
	
		$list = array();
		foreach ($rows as $rs) {
				$list[] = array('id'      => $rs['id'], 
								'name'    => $rs['name'].' ('.$rs['more'].')', 
								'company' => 2); // 2 is for user
		}		
        return $list;	
	}
	
	/**
	* Get all Users defined for specified Tsg
	*
	* @return array $list: easyui json format ["total", "rows"]
	*/
	public function findUsersForTsg($id)
	{
		$list = array("total" => 0, "rows" => array());
		$query =  $this->getEntityManager()->createQuery(
			'SELECT o.name, o.id, o.username as username, o.password as pname FROM AcmeZayavkiBundle:Usertsg u, AcmeZayavkiBundle:User  o where u.userid = o.id and u.tsgid = :id order by o.name'
			);
		$query->setParameter('id', $id);
		$list["rows"] = $query->getResult();	
		$list['total'] = count($list['rows']); 	

        return $list;	
	}
	
	/**
	* Get entity User
	*
	* @param int $id
	* @return array $res
	*/		
	public function findEntity($id)
	{	
	    // $query = $this->getEntityManager()->createQuery(
			// 'SELECT u.id, u.name, u.username, u.password FROM AcmeZayavkiBundle:User u WHERE u.id = :id'
			// );
		// $query->setParameter('id', $id);
		// $entity = current($query->getResult());
		
		// if (!$entity) {
			// $entity = array('name'     => '',
							// 'username' => '',
							// 'password' => '',					  
							// 'id'       => 0
				// );
		// }
		$entity->find($id);
		return $entity;
	}	
	
	/**
	* Save User
	*
	* @param int $id
	* @param array $var  
	* @return entity id
	*/	
	public function saveEntity( $id, $var)
	{
		$entity = $this->find($id);	
		if (!$entity) {
			$entity = new User();
		}
			
		$entity->setName( $var['name'] ); 
		$entity->setUsername( $var['username']);
		$entity->setPassword( $var['password']);
				
		$em = $this->getEntityManager();				
		$em->persist($entity);
		$em->flush();
		return $entity->getId();			
	}
	/**
	* Update tsgs for User
	*
	* @param int $id
	* @param String list = list of ids: ;1;22;567;
	*/	
	public function setTsgs( $id, $list)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('delete from Usertsg where userid = :userid', array('userid' => $id));		
		$this->getEntityManager()->getConnection()->executeUpdate("insert into Usertsg (userid, tsgid) select :userid, TSGId from TSGInfo where LOCATE(concat(';',TsgId,';'), :tlist) > 0", 
			array('userid' => $id, 
				  'tlist' => $list));

	}
	/**
	* Users set deleted on
	*
	* @param int $id
	* @return entity id
	*/
	public function deleteEntity( $id)
	{	
		$this->getEntityManager()->getConnection()->executeUpdate('update User set deleted = 1 where id = :id', array('id' => $id));			 
		return $id;
	}		
}