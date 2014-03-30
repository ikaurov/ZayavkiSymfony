<?php

namespace Acme\ZayavkiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketsComment
 *
 * @ORM\Table(name="Tickets_comment", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 */
class TicketsComment
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
     * @ORM\Column(name="ticketid", type="integer", nullable=true)
     */
    private $ticketid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dcreate", type="datetime", nullable=true)
     */
    private $dcreate;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="kind", type="integer", nullable=true)
     */
    private $kind = '0';



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
     * Set ticketid
     *
     * @param integer $ticketid
     * @return TicketsComment
     */
    public function setTicketid($ticketid)
    {
        $this->ticketid = $ticketid;

        return $this;
    }

    /**
     * Get ticketid
     *
     * @return integer 
     */
    public function getTicketid()
    {
        return $this->ticketid;
    }

    /**
     * Set dcreate
     *
     * @param \DateTime $dcreate
     * @return TicketsComment
     */
    public function setDcreate($dcreate)
    {
        $this->dcreate = $dcreate;

        return $this;
    }

    /**
     * Get dcreate
     *
     * @return \DateTime 
     */
    public function getDcreate()
    {
        return $this->dcreate;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return TicketsComment
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
     * Set userid
     *
     * @param integer $userid
     * @return TicketsComment
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
     * Set kind
     *
     * @param integer $kind
     * @return TicketsComment
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
}
