<?php

namespace Acme\ZayavkiBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Operators
 * 
 * @ORM\Table(name="Operators", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="name", columns={"name"})})
 * @ORM\Entity(repositoryClass="Acme\ZayavkiBundle\Entity\OperatorsRepository")
 */
class Operators implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=20, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="acckind", type="integer", nullable=false)
     */
    private $acckind = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ownid", type="integer", nullable=false)
     */
    private $ownid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=false)
     */
    private $active = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    private $deleted = '0';



	public function __construct($login, $password, $salt, array $roles)
	{
		$this->login    = $login;
		$this->password = $password;
		
	}	
	
    /**
     * Get id
     *
     * @return integer 
     */	 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Operators
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Operators
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Operators
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set acckind
     *
     * @param integer $acckind
     * @return Operators
     */
    public function setAcckind($acckind)
    {
        $this->acckind = $acckind;

        return $this;
    }

    /**
     * Get acckind
     *
     * @return integer 
     */
    public function getAcckind()
    {
        return $this->acckind;
    }

    /**
     * Set ownid
     *
     * @param integer $ownid
     * @return Operators
     */
    public function setOwnid($ownid)
    {
        $this->ownid = $ownid;

        return $this;
    }

    /**
     * Get ownid
     *
     * @return integer 
     */
    public function getOwnid()
    {
        return $this->ownid;
    }

    /**
     * Set active
     *
     * @param integer $active
     * @return Operators
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set deleted
     *
     * @param integer $deleted
     * @return Operators
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return integer 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
	
    public function getRoles()
    {
        return '';
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }	
	
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }		
		
}
