<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comprop 
 *
 * @ORM\Table(name="Comprop", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="kind", columns={"kind", "uin", "name"})})
 * @ORM\Entity(repositoryClass="Acme\ZayavkiBundle\Entity\CompropRepository")
 */
class Comprop
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
     * @var integer
     *
     * @ORM\Column(name="kind", type="integer", nullable=false)
     */
    private $kind = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="uin", type="integer", nullable=false)
     */
    private $uin = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;



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
     * Set kind
     *
     * @param integer $kind
     * @return Comprop
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get kind
     *
     * @return integer 
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set uin
     *
     * @param integer $uin
     * @return Comprop
     */
    public function setUin($uin)
    {
        $this->uin = $uin;

        return $this;
    }

    /**
     * Get uin
     *
     * @return integer 
     */
    public function getUin()
    {
        return $this->uin;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Comprop
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
}
