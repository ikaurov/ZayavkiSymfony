<?php
 
namespace Acme\ZayavkiBundle\Model;
 
class Db
{
    private $dsn;
    private $username;
    private $password;
 
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
    }
 
    public function setPassword($password)
    {
        $this->password = $password;
    }
 
    public function setUsername($username)
    {
        $this->username = $username;
    }
 
    /** @return \PDO */
    public function getPDO()
    {
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
						 \PDO:: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

		//return new \PDO('mysql:host=localhost;port=3306;dbname=test', 'root', '123456', $options);		
        return new \PDO($this->dsn, $this->username, $this->password, $options);
    }
}