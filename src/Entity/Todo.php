<?php

namespace Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Helper\Constants;
use JsonSerializable;

/**
 * Todo entity class
 *
 * @ORM\Table(name="todos")
 * @ORM\Entity
 */
class Todo implements JsonSerializable
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

	/**
	 * @ORM\Column(type=Types::BOOLEAN, nullable=false)
	 */
	private $completed;

	/**
	 * @ORM\Column(type=Types::DATETIME_MUTABLE)
	 * @var \DateTime
	 */
	private $completed_on;

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

	public function getcompleted()
	{
		return $this->completed;
	}

	public function setcompleted($completed)
	{
		$this->completed = $completed;
		return $this;
	}

	public function getcompleted_on()
	{
		return $this->completed_on
			? $this->completed_on->format(Constants::DATE_FORMAT)
			: null;
	}

	public function setcompleted_on($completed_on)
	{
		$this->completed_on = $completed_on;
		return $this;
	}

	public function jsonSerialize()
	{
		return [
			'id' => $this->getid(),
			'user_id' => $this->getuser_id(),
			'description' => $this->getdescription(),
			'completed' => $this->getcompleted(),
			'completed_on' => $this->getcompleted_on(),
		];
	}
}