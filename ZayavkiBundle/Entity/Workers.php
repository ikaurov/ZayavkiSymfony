<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workers
 * 
 * @ORM\Table(name="Workers", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="name", columns={"name", "ownid", "deleted", "profid"})})
 * @ORM\Entity(repositoryClass="Acme\ZayavkiBundle\Entity\WorkersRepository")
 */
class Workers
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
     * @var integer
     *
     * @ORM\Column(name="ownid", type="integer", nullable=false)
     */
    private $ownid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="deleted", type="integer", nullable=false)
     */
    private $deleted = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="profid", type="integer", nullable=false)
     */
    private $profid = '0';



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
     * @return Workers
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
     * Set ownid
     *
     * @param integer $ownid
     * @return Workers
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
     * Set deleted
     *
     * @param integer $deleted
     * @return Workers
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

    /**
     * Set profid
     *
     * @param integer $profid
     * @return Workers
     */
    public function setProfid($profid)
    {
        $this->profid = $profid;

        return $this;
    }

    /**
     * Get profid
     *
     * @return integer 
     */
    public function getProfid()
    {
        return $this->profid;
    }
}
