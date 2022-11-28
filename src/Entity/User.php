<?php

namespace Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * User entity class
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User
{

	/**
	 * @ORM\Column(type=Types::INTEGER, nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\Column(length=255)
	 */
	private $username;

	/**
	 * @ORM\Column(length=255)
	 */
	private $password;

	public function getid()
	{
		return $this->id;
	}

	public function getusername()
	{
		return $this->username;
	}

	public function setusername($username)
	{
		$this->username = $username;
		return $this;
	}

	public function getpassword()
	{
		return $this->password;
	}

	public function setpassword($password)
	{
		$this->password = $password;
		return $this;
	}

}