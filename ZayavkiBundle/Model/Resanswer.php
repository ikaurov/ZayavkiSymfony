<?php

namespace Acme\ZayavkiBundle\Model;

class Resanswer
{
	private $success;
	private $message;
	private $id;
	
	public function __construct($success = true, $message = '', $id = 0)
	{
		$this->success = $success;
		$this->message = $message;
		$this->id      = $id;
	}
	
	public function getRet()
	{
		return array('message' => $this->message, 'success' => ($this->success?'true' : 'false' ), 'id' => $this->id);
	}
	
	static public function getRetJSON($msg, $success, $id)
	{
		return json_encode(array('message' => $msg, 'success' => ($success?'true' : 'false' ), 'id' => $id));
	}	
	
	public function setSuccess($value)
	{
		$this->success = $value;
	}
	
	public function setMessage($value)
	{
		$this->message = $value;
	}

	public function setId($value)
	{
		$this->id = $value;
	}	
}



?>