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
	/**
	* UserProviderInterface methods
	*/
    public function loadUserByUsername($username)
    {
		$user = $this->findOneBy(
			array('username' => $username)
		);
	
        return $user;
    }
	/**
	* UserProviderInterface methods
	*/
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
	/**
	* UserProviderInterface methods
	*/
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
		$entity = $this->find($id);
		if (!$entity) {
			$entity = new User(); 
		}
		return array('name'     => $entity->getName(),
					 'username' => $entity->getUsername(),
					 'password' => $entity->getPassword(),					  
					 'id'       => $entity->getId()
				 );
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

	/**
	* Remember specified column width for user
	*
	* @param int $userid
	* @param string $name
	* @param int $width
	*/
	public function setColumnWidth($userid, $name, $width)
	{	
		$stmt = $this->getEntityManager()->getConnection()->prepare('CALL Setcolwidth(:userid, :name, :width );');
		$stmt->bindValue('userid', $userid);	
		$stmt->bindValue('name', $name);	
		$stmt->bindValue('width', $width);	
		$stmt->execute(); 

	}	
	
	public function getColumnsSize($userid)
	{
		$colsize = array( 'state' => 40,
						'tsgname' => 150,
						'nr'      => 70,
						'dstart'  => 110,
						'cname'   => 130,
						'sname'   => 130,
						'account' => 50,
						'fio'	 => 130,
						'address' => 180,
						'message' => 200,
						'wname'   => 110,
						'dplan'   => 130
					);
		
		$stmt = $this->getEntityManager()->getConnection()->prepare('select * from Usercol where userid = :userid');
		$stmt->bindValue('userid', $userid);	
		$stmt->execute(); 
		
		$rows = $stmt->fetchAll();
       	foreach ($rows as $rs){
       		$colsize[ $rs['name'] ] = $rs['width'];
   		}
		
		return $colsize;
	}
	
}