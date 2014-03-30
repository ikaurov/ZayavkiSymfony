<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usertsg
 *
 * @ORM\Table(name="Usertsg", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity 
 */
class Usertsg
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
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="tsgid", type="integer", nullable=true)
     */
    private $tsgid;



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
     * Set userid
     *
     * @param integer $userid
     * @return Usertsg
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set tsgid
     *
     * @param integer $tsgid
     * @return Usertsg
     */
    public function setTsgid($tsgid)
    {
        $this->tsgid = $tsgid;

        return $this;
    }

    /**
     * Get tsgid
     *
     * @return integer 
     */
    public function getTsgid()
    {
        return $this->tsgid;
    }
}
