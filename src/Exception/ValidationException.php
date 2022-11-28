<?php

namespace Exception;

/**
 * Validation Exception Class
 *
 */
class ValidationException extends \Exception
{
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string|null
	 */
	private $redirectPath;

	public function __construct($message = "", $type = "error", $redirectPath = null)
	{
		parent::__construct($message);
		$this->type = $type;
		$this->redirectPath = $redirectPath;
	}

	/**
	 * Get type of validation error.
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the redirect path on this Exception.
	 *
	 * @return mixed|string|null
	 */
	public function getRedirectPath()
	{
		return $this->redirectPath;
	}

}