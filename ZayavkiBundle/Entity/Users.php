<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users 
 *
 * @ORM\Table(name="Users", uniqueConstraints={@ORM\UniqueConstraint(name="UK_Users", columns={"UserName", "TSGCode"})}, indexes={@ORM\Index(name="IX_Users_TSGCode", columns={"TSGCode"}), @ORM\Index(name="IX_Users_UserName", columns={"UserName"})})
 * @ORM\Entity
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="UserId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="TSGCode", type="string", length=20, nullable=false)
     */
    private $tsgcode;

    /**
     * @var string
     *
     * @ORM\Column(name="UserName", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=50, nullable=false)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isBlocked", type="boolean", nullable=false)
     */
    private $isblocked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="Type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isReset", type="boolean", nullable=false)
     */
    private $isreset = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="NewPassword", type="string", length=50, nullable=true)
     */
    private $newpassword;

    /**
     * @var integer
     *
     * @ORM\Column(name="failedCount", type="integer", nullable=false)
     */
    private $failedcount = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FrozenDate", type="datetime", nullable=true)
     */
    private $frozendate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="WaitRegistry", type="boolean", nullable=false)
     */
    private $waitregistry = '0';



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
     * Set tsgcode
     *
     * @param string $tsgcode
     * @return Users
     */
    public function setTsgcode($tsgcode)
    {
        $this->tsgcode = $tsgcode;

        return $this;
    }

    /**
     * Get tsgcode
     *
     * @return string 
     */
    public function getTsgcode()
    {
        return $this->tsgcode;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
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
     * Set isblocked
     *
     * @param boolean $isblocked
     * @return Users
     */
    public function setIsblocked($isblocked)
    {
        $this->isblocked = $isblocked;

        return $this;
    }

    /**
     * Get isblocked
     *
     * @return boolean 
     */
    public function getIsblocked()
    {
        return $this->isblocked;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Users
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isreset
     *
     * @param boolean $isreset
     * @return Users
     */
    public function setIsreset($isreset)
    {
        $this->isreset = $isreset;

        return $this;
    }

    /**
     * Get isreset
     *
     * @return boolean 
     */
    public function getIsreset()
    {
        return $this->isreset;
    }

    /**
     * Set newpassword
     *
     * @param string $newpassword
     * @return Users
     */
    public function setNewpassword($newpassword)
    {
        $this->newpassword = $newpassword;

        return $this;
    }

    /**
     * Get newpassword
     *
     * @return string 
     */
    public function getNewpassword()
    {
        return $this->newpassword;
    }

    /**
     * Set failedcount
     *
     * @param integer $failedcount
     * @return Users
     */
    public function setFailedcount($failedcount)
    {
        $this->failedcount = $failedcount;

        return $this;
    }

    /**
     * Get failedcount
     *
     * @return integer 
     */
    public function getFailedcount()
    {
        return $this->failedcount;
    }

    /**
     * Set frozendate
     *
     * @param \DateTime $frozendate
     * @return Users
     */
    public function setFrozendate($frozendate)
    {
        $this->frozendate = $frozendate;

        return $this;
    }

    /**
     * Get frozendate
     *
     * @return \DateTime 
     */
    public function getFrozendate()
    {
        return $this->frozendate;
    }

    /**
     * Set waitregistry
     *
     * @param boolean $waitregistry
     * @return Users
     */
    public function setWaitregistry($waitregistry)
    {
        $this->waitregistry = $waitregistry;

        return $this;
    }

    /**
     * Get waitregistry
     *
     * @return boolean 
     */
    public function getWaitregistry()
    {
        return $this->waitregistry;
    }
}
