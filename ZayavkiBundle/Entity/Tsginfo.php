<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tsginfo
 * @ORM\Table(name="TSGInfo", uniqueConstraints={@ORM\UniqueConstraint(name="TSGId", columns={"TSGId"})}, indexes={@ORM\Index(name="TSGCode", columns={"TSGCode", "TSGName"})})
 * @ORM\Entity(repositoryClass="Acme\ZayavkiBundle\Entity\TsginfoRepository")
 */
class Tsginfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="TSGId", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tsgid;

    /**
     * @var string
     *
     * @ORM\Column(name="TSGCode", type="string", length=20, nullable=false)
     */
    private $tsgcode;

    /**
     * @var string
     *
     * @ORM\Column(name="TSGName", type="string", length=100, nullable=false)
     */
    private $tsgname;

    /**
     * @var string
     *
     * @ORM\Column(name="TSGAddress", type="string", length=100, nullable=false)
     */
    private $tsgaddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="OpenPeriod", type="integer", nullable=false)
     */
    private $openperiod = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="Mode", type="integer", nullable=false)
     */
    private $mode = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="head", type="integer", nullable=false)
     */
    private $head = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="lk", type="integer", nullable=false)
     */
    private $lk = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="oldDBId", type="integer", nullable=false)
     */
    private $olddbid;

    /**
     * @var integer
     *
     * @ORM\Column(name="rootid", type="integer", nullable=false)
     */
    private $rootid;

    /**
     * @var integer
     *
     * @ORM\Column(name="DateUpdate", type="integer", nullable=false)
     */
    private $dateupdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="DateSetMode", type="integer", nullable=false)
     */
    private $datesetmode;



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
     * Set tsgcode
     *
     * @param string $tsgcode
     * @return Tsginfo
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
     * Set tsgname
     *
     * @param string $tsgname
     * @return Tsginfo
     */
    public function setTsgname($tsgname)
    {
        $this->tsgname = $tsgname;

        return $this;
    }

    /**
     * Get tsgname
     *
     * @return string 
     */
    public function getTsgname()
    {
        return $this->tsgname;
    }

    /**
     * Set tsgaddress
     *
     * @param string $tsgaddress
     * @return Tsginfo
     */
    public function setTsgaddress($tsgaddress)
    {
        $this->tsgaddress = $tsgaddress;

        return $this;
    }

    /**
     * Get tsgaddress
     *
     * @return string 
     */
    public function getTsgaddress()
    {
        return $this->tsgaddress;
    }

    /**
     * Set openperiod
     *
     * @param integer $openperiod
     * @return Tsginfo
     */
    public function setOpenperiod($openperiod)
    {
        $this->openperiod = $openperiod;

        return $this;
    }

    /**
     * Get openperiod
     *
     * @return integer 
     */
    public function getOpenperiod()
    {
        return $this->openperiod;
    }

    /**
     * Set mode
     *
     * @param integer $mode
     * @return Tsginfo
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return integer 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set head
     *
     * @param integer $head
     * @return Tsginfo
     */
    public function setHead($head)
    {
        $this->head = $head;

        return $this;
    }

    /**
     * Get head
     *
     * @return integer 
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set lk
     *
     * @param integer $lk
     * @return Tsginfo
     */
    public function setLk($lk)
    {
        $this->lk = $lk;

        return $this;
    }

    /**
     * Get lk
     *
     * @return integer 
     */
    public function getLk()
    {
        return $this->lk;
    }

    /**
     * Set olddbid
     *
     * @param integer $olddbid
     * @return Tsginfo
     */
    public function setOlddbid($olddbid)
    {
        $this->olddbid = $olddbid;

        return $this;
    }

    /**
     * Get olddbid
     *
     * @return integer 
     */
    public function getOlddbid()
    {
        return $this->olddbid;
    }

    /**
     * Set rootid
     *
     * @param integer $rootid
     * @return Tsginfo
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
     * Set dateupdate
     *
     * @param integer $dateupdate
     * @return Tsginfo
     */
    public function setDateupdate($dateupdate)
    {
        $this->dateupdate = $dateupdate;

        return $this;
    }

    /**
     * Get dateupdate
     *
     * @return integer 
     */
    public function getDateupdate()
    {
        return $this->dateupdate;
    }

    /**
     * Set datesetmode
     *
     * @param integer $datesetmode
     * @return Tsginfo
     */
    public function setDatesetmode($datesetmode)
    {
        $this->datesetmode = $datesetmode;

        return $this;
    }

    /**
     * Get datesetmode
     *
     * @return integer 
     */
    public function getDatesetmode()
    {
        return $this->datesetmode;
    }
}
