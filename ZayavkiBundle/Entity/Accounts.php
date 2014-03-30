<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accounts
 *
 * @ORM\Table(name="Accounts", indexes={@ORM\Index(name="IX_Accounts_UserId", columns={"UserId"}), @ORM\Index(name="IX_Accounts", columns={"Account"}), @ORM\Index(name="IK_Accounts_DisplayNumber", columns={"DisplayNumber"}), @ORM\Index(name="IK_Accounts_isUpdated", columns={"isUpdated"})})
 * @ORM\Entity
 */ 
class Accounts
{
    /**
     * @var integer
     *
     * @ORM\Column(name="AccountId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $accountid;

    /**
     * @var integer
     *
     * @ORM\Column(name="UserId", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="Account", type="string", length=50, nullable=false)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="DisplayNumber", type="string", length=50, nullable=true)
     */
    private $displaynumber;

    /**
     * @var string
     *
     * @ORM\Column(name="Question", type="string", length=255, nullable=true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="Answer", type="string", length=255, nullable=true)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="AddressId", type="integer", nullable=true)
     */
    private $addressid;

    /**
     * @var string
     *
     * @ORM\Column(name="ObjectAddress", type="string", length=500, nullable=true)
     */
    private $objectaddress;

    /**
     * @var string
     *
     * @ORM\Column(name="RoomType", type="string", length=50, nullable=true)
     */
    private $roomtype;

    /**
     * @var string
     *
     * @ORM\Column(name="RoomNumber", type="string", length=50, nullable=true)
     */
    private $roomnumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="DoubleCntr", type="boolean", nullable=false)
     */
    private $doublecntr = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="FIO", type="string", length=250, nullable=true)
     */
    private $fio;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var float
     *
     * @ORM\Column(name="Debt", type="float", precision=10, scale=0, nullable=true)
     */
    private $debt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isUpdated", type="boolean", nullable=false)
     */
    private $isupdated = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="RegistryFIO", type="string", length=255, nullable=true)
     */
    private $registryfio;

    /**
     * @var string
     *
     * @ORM\Column(name="OwnerFIO", type="string", length=255, nullable=true)
     */
    private $ownerfio;

    /**
     * @var string
     *
     * @ORM\Column(name="RegistryEmail", type="string", length=50, nullable=true)
     */
    private $registryemail;

    /**
     * @var string
     *
     * @ORM\Column(name="RegistrySquare", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $registrysquare;

    /**
     * @var string
     *
     * @ORM\Column(name="RegistryMobile", type="string", length=100, nullable=true)
     */
    private $registrymobile;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=50, nullable=true)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateRegistry", type="datetime", nullable=true)
     */
    private $dateregistry;

    /**
     * @var string
     *
     * @ORM\Column(name="Square", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $square;

    /**
     * @var string
     *
     * @ORM\Column(name="Mobile", type="string", length=100, nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="Phones", type="string", length=100, nullable=true)
     */
    private $phones;

    /**
     * @var boolean
     *
     * @ORM\Column(name="DontUpdate", type="boolean", nullable=false)
     */
    private $dontupdate = '0';



    /**
     * Get accountid
     *
     * @return integer 
     */
    public function getAccountid()
    {
        return $this->accountid;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Accounts
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
     * Set account
     *
     * @param string $account
     * @return Accounts
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set displaynumber
     *
     * @param string $displaynumber
     * @return Accounts
     */
    public function setDisplaynumber($displaynumber)
    {
        $this->displaynumber = $displaynumber;

        return $this;
    }

    /**
     * Get displaynumber
     *
     * @return string 
     */
    public function getDisplaynumber()
    {
        return $this->displaynumber;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return Accounts
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     * @return Accounts
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Accounts
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
     * Set addressid
     *
     * @param integer $addressid
     * @return Accounts
     */
    public function setAddressid($addressid)
    {
        $this->addressid = $addressid;

        return $this;
    }

    /**
     * Get addressid
     *
     * @return integer 
     */
    public function getAddressid()
    {
        return $this->addressid;
    }

    /**
     * Set objectaddress
     *
     * @param string $objectaddress
     * @return Accounts
     */
    public function setObjectaddress($objectaddress)
    {
        $this->objectaddress = $objectaddress;

        return $this;
    }

    /**
     * Get objectaddress
     *
     * @return string 
     */
    public function getObjectaddress()
    {
        return $this->objectaddress;
    }

    /**
     * Set roomtype
     *
     * @param string $roomtype
     * @return Accounts
     */
    public function setRoomtype($roomtype)
    {
        $this->roomtype = $roomtype;

        return $this;
    }

    /**
     * Get roomtype
     *
     * @return string 
     */
    public function getRoomtype()
    {
        return $this->roomtype;
    }

    /**
     * Set roomnumber
     *
     * @param string $roomnumber
     * @return Accounts
     */
    public function setRoomnumber($roomnumber)
    {
        $this->roomnumber = $roomnumber;

        return $this;
    }

    /**
     * Get roomnumber
     *
     * @return string 
     */
    public function getRoomnumber()
    {
        return $this->roomnumber;
    }

    /**
     * Set doublecntr
     *
     * @param boolean $doublecntr
     * @return Accounts
     */
    public function setDoublecntr($doublecntr)
    {
        $this->doublecntr = $doublecntr;

        return $this;
    }

    /**
     * Get doublecntr
     *
     * @return boolean 
     */
    public function getDoublecntr()
    {
        return $this->doublecntr;
    }

    /**
     * Set fio
     *
     * @param string $fio
     * @return Accounts
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string 
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Accounts
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
     * Set debt
     *
     * @param float $debt
     * @return Accounts
     */
    public function setDebt($debt)
    {
        $this->debt = $debt;

        return $this;
    }

    /**
     * Get debt
     *
     * @return float 
     */
    public function getDebt()
    {
        return $this->debt;
    }

    /**
     * Set isupdated
     *
     * @param boolean $isupdated
     * @return Accounts
     */
    public function setIsupdated($isupdated)
    {
        $this->isupdated = $isupdated;

        return $this;
    }

    /**
     * Get isupdated
     *
     * @return boolean 
     */
    public function getIsupdated()
    {
        return $this->isupdated;
    }

    /**
     * Set registryfio
     *
     * @param string $registryfio
     * @return Accounts
     */
    public function setRegistryfio($registryfio)
    {
        $this->registryfio = $registryfio;

        return $this;
    }

    /**
     * Get registryfio
     *
     * @return string 
     */
    public function getRegistryfio()
    {
        return $this->registryfio;
    }

    /**
     * Set ownerfio
     *
     * @param string $ownerfio
     * @return Accounts
     */
    public function setOwnerfio($ownerfio)
    {
        $this->ownerfio = $ownerfio;

        return $this;
    }

    /**
     * Get ownerfio
     *
     * @return string 
     */
    public function getOwnerfio()
    {
        return $this->ownerfio;
    }

    /**
     * Set registryemail
     *
     * @param string $registryemail
     * @return Accounts
     */
    public function setRegistryemail($registryemail)
    {
        $this->registryemail = $registryemail;

        return $this;
    }

    /**
     * Get registryemail
     *
     * @return string 
     */
    public function getRegistryemail()
    {
        return $this->registryemail;
    }

    /**
     * Set registrysquare
     *
     * @param string $registrysquare
     * @return Accounts
     */
    public function setRegistrysquare($registrysquare)
    {
        $this->registrysquare = $registrysquare;

        return $this;
    }

    /**
     * Get registrysquare
     *
     * @return string 
     */
    public function getRegistrysquare()
    {
        return $this->registrysquare;
    }

    /**
     * Set registrymobile
     *
     * @param string $registrymobile
     * @return Accounts
     */
    public function setRegistrymobile($registrymobile)
    {
        $this->registrymobile = $registrymobile;

        return $this;
    }

    /**
     * Get registrymobile
     *
     * @return string 
     */
    public function getRegistrymobile()
    {
        return $this->registrymobile;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Accounts
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateregistry
     *
     * @param \DateTime $dateregistry
     * @return Accounts
     */
    public function setDateregistry($dateregistry)
    {
        $this->dateregistry = $dateregistry;

        return $this;
    }

    /**
     * Get dateregistry
     *
     * @return \DateTime 
     */
    public function getDateregistry()
    {
        return $this->dateregistry;
    }

    /**
     * Set square
     *
     * @param string $square
     * @return Accounts
     */
    public function setSquare($square)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Get square
     *
     * @return string 
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Accounts
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set phones
     *
     * @param string $phones
     * @return Accounts
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get phones
     *
     * @return string 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set dontupdate
     *
     * @param boolean $dontupdate
     * @return Accounts
     */
    public function setDontupdate($dontupdate)
    {
        $this->dontupdate = $dontupdate;

        return $this;
    }

    /**
     * Get dontupdate
     *
     * @return boolean 
     */
    public function getDontupdate()
    {
        return $this->dontupdate;
    }
}
