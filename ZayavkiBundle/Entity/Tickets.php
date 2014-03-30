<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tickets 
 *
 * @ORM\Table(name="Tickets", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})}, indexes={@ORM\Index(name="TSGCode", columns={"tsgcode"}), @ORM\Index(name="TSGID", columns={"tsgid"}), @ORM\Index(name="NR", columns={"nr"})})
 * @ORM\Entity(repositoryClass="Acme\ZayavkiBundle\Entity\TicketsRepository")
 */
class Tickets
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
     * @ORM\Column(name="nr", type="integer", nullable=true)
     */
    private $nr = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="alert", type="integer", nullable=true)
     */
    private $alert = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="statusid", type="integer", nullable=true)
     */
    private $statusid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="substatusid", type="integer", nullable=true)
     */
    private $substatusid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryid", type="integer", nullable=true)
     */
    private $categoryid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="preftime", type="string", length=50, nullable=true)
     */
    private $preftime;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="tsgcode", type="string", length=20, nullable=true)
     */
    private $tsgcode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dstart", type="datetime", nullable=true)
     */
    private $dstart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dstop", type="datetime", nullable=true)
     */
    private $dstop;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dwork", type="datetime", nullable=true)
     */
    private $dwork;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dplan", type="datetime", nullable=true)
     */
    private $dplan;

    /**
     * @var integer
     *
     * @ORM\Column(name="workerid", type="integer", nullable=true)
     */
    private $workerid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="usernote", type="text", nullable=true)
     */
    private $usernote;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="tsgid", type="integer", nullable=true)
     */
    private $tsgid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="dispid", type="integer", nullable=true)
     */
    private $dispid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="lastnote", type="string", length=200, nullable=true)
     */
    private $lastnote = '';

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="fopen", type="integer", nullable=true)
     */
    private $fopen = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="stopid", type="integer", nullable=true)
     */
    private $stopid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="rootid", type="integer", nullable=true)
     */
    private $rootid = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="podal", type="string", length=50, nullable=true)
     */
    private $podal;

    /**
     * @var integer
     *
     * @ORM\Column(name="deleted", type="integer", nullable=true)
     */
    private $deleted = '0';



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
     * Set nr
     *
     * @param integer $nr
     * @return Tickets
     */
    public function setNr($nr)
    {
        $this->nr = $nr;

        return $this;
    }

    /**
     * Get nr
     *
     * @return integer 
     */
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * Set alert
     *
     * @param integer $alert
     * @return Tickets
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;

        return $this;
    }

    /**
     * Get alert
     *
     * @return integer 
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * Set statusid
     *
     * @param integer $statusid
     * @return Tickets
     */
    public function setStatusid($statusid)
    {
        $this->statusid = $statusid;

        return $this;
    }

    /**
     * Get statusid
     *
     * @return integer 
     */
    public function getStatusid()
    {
        return $this->statusid;
    }

    /**
     * Set substatusid
     *
     * @param integer $substatusid
     * @return Tickets
     */
    public function setSubstatusid($substatusid)
    {
        $this->substatusid = $substatusid;

        return $this;
    }

    /**
     * Get substatusid
     *
     * @return integer 
     */
    public function getSubstatusid()
    {
        return $this->substatusid;
    }

    /**
     * Set categoryid
     *
     * @param integer $categoryid
     * @return Tickets
     */
    public function setCategoryid($categoryid)
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    /**
     * Get categoryid
     *
     * @return integer 
     */
    public function getCategoryid()
    {
        return $this->categoryid;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Tickets
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Tickets
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set preftime
     *
     * @param string $preftime
     * @return Tickets
     */
    public function setPreftime($preftime)
    {
        $this->preftime = $preftime;

        return $this;
    }

    /**
     * Get preftime
     *
     * @return string 
     */
    public function getPreftime()
    {
        return $this->preftime;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Tickets
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
     * Set tsgcode
     *
     * @param string $tsgcode
     * @return Tickets
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
     * Set dstart
     *
     * @param \DateTime $dstart
     * @return Tickets
     */
    public function setDstart($dstart)
    {
        $this->dstart = $dstart;

        return $this;
    }

    /**
     * Get dstart
     *
     * @return \DateTime 
     */
    public function getDstart()
    {
        return $this->dstart;
    }

    /**
     * Set dstop
     *
     * @param \DateTime $dstop
     * @return Tickets
     */
    public function setDstop($dstop)
    {
        $this->dstop = $dstop;

        return $this;
    }

    /**
     * Get dstop
     *
     * @return \DateTime 
     */
    public function getDstop()
    {
        return $this->dstop;
    }

    /**
     * Set dwork
     *
     * @param \DateTime $dwork
     * @return Tickets
     */
    public function setDwork($dwork)
    {
        $this->dwork = $dwork;

        return $this;
    }

    /**
     * Get dwork
     *
     * @return \DateTime 
     */
    public function getDwork()
    {
        return $this->dwork;
    }

    /**
     * Set dplan
     *
     * @param \DateTime $dplan
     * @return Tickets
     */
    public function setDplan($dplan)
    {
        $this->dplan = $dplan;

        return $this;
    }

    /**
     * Get dplan
     *
     * @return \DateTime 
     */
    public function getDplan()
    {
        return $this->dplan;
    }

    /**
     * Set workerid
     *
     * @param integer $workerid
     * @return Tickets
     */
    public function setWorkerid($workerid)
    {
        $this->workerid = $workerid;

        return $this;
    }

    /**
     * Get workerid
     *
     * @return integer 
     */
    public function getWorkerid()
    {
        return $this->workerid;
    }

    /**
     * Set usernote
     *
     * @param string $usernote
     * @return Tickets
     */
    public function setUsernote($usernote)
    {
        $this->usernote = $usernote;

        return $this;
    }

    /**
     * Get usernote
     *
     * @return string 
     */
    public function getUsernote()
    {
        return $this->usernote;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Tickets
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set tsgid
     *
     * @param integer $tsgid
     * @return Tickets
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

    /**
     * Set dispid
     *
     * @param integer $dispid
     * @return Tickets
     */
    public function setDispid($dispid)
    {
        $this->dispid = $dispid;

        return $this;
    }

    /**
     * Get dispid
     *
     * @return integer 
     */
    public function getDispid()
    {
        return $this->dispid;
    }

    /**
     * Set lastnote
     *
     * @param string $lastnote
     * @return Tickets
     */
    public function setLastnote($lastnote)
    {
        $this->lastnote = $lastnote;

        return $this;
    }

    /**
     * Get lastnote
     *
     * @return string 
     */
    public function getLastnote()
    {
        return $this->lastnote;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Tickets
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Tickets
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set fopen
     *
     * @param integer $fopen
     * @return Tickets
     */
    public function setFopen($fopen)
    {
        $this->fopen = $fopen;

        return $this;
    }

    /**
     * Get fopen
     *
     * @return integer 
     */
    public function getFopen()
    {
        return $this->fopen;
    }

    /**
     * Set stopid
     *
     * @param integer $stopid
     * @return Tickets
     */
    public function setStopid($stopid)
    {
        $this->stopid = $stopid;

        return $this;
    }

    /**
     * Get stopid
     *
     * @return integer 
     */
    public function getStopid()
    {
        return $this->stopid;
    }

    /**
     * Set rootid
     *
     * @param integer $rootid
     * @return Tickets
     */
    public function setRootid($rootid)
    {
        $this->rootid = $rootid;

        return $this;
    }

    /**
     * Get rootid
     *
     * @return integer 
     */
    public function getRootid()
    {
        return $this->rootid;
    }

    /**
     * Set podal
     *
     * @param string $podal
     * @return Tickets
     */
    public function setPodal($podal)
    {
        $this->podal = $podal;

        return $this;
    }

    /**
     * Get podal
     *
     * @return string 
     */
    public function getPodal()
    {
        return $this->podal;
    }

    /**
     * Set deleted
     *
     * @param integer $deleted
     * @return Tickets
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
}
