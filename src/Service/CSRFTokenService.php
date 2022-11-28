<?php

namespace Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Authentication Service Class
 * Handle state of the authentication.
 *
 */
class CSRFTokenService
{

	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @param Session $session
	 */
	public function __construct($session)
	{
		$this->session = $session;
	}

	/**
	 * Validate CSRF token.
	 *
	 * @param $token
	 * @return bool
	 */
	public function validateToken($token)
	{
		return $token === $this->getToken();
	}

	/**
	 * Get CSRF current token.
	 *
	 * @return string|null
	 */
	public function getToken()
	{
		return $this->session->get('CSRFToken');
	}

	/**
	 * Regenerate CSRF token.
	 *
	 * @return string
	 */
	public function regenerateToken()
	{
		$token = md5(uniqid(mt_rand(), true).time());
		$this->session
			->set('CSRFToken', $token);
		return $token;
	}

}