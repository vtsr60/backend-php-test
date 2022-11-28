<?php

namespace Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Todo entity class
 *
 * @ORM\Table(name="todos")
 * @ORM\Entity
 */
class Todo
{

	/**
	 * @ORM\Column(type=Types::INTEGER, nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\Column(type=Types::INTEGER, nullable=false)
	 */
	private $user_id;

	/**
	 * @ORM\Column(length=255)
	 */
	private $description;

	public function getid()
	{
		return $this->id;
	}

	public function getuser_id()
	{
		return $this->user_id;
	}

	public function setuser_id($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}

	public function getdescription()
	{
		return $this->description;
	}

	public function setdescription($description)
	{
		$this->description = $description;
		return $this;
	}

}