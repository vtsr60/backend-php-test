<?php

namespace Service;

use Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Authentication Service Class
 * Handle state of the authentication.
 *
 */
class AuthService
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
	 * Validated the user with given password
	 *
	 * @param User $user
	 * @param $password
	 * @return bool
	 */
	public function validatePassword($user, $password)
	{
		return password_verify($password, $user->getpassword());
	}

	/**
	 * Check user is logged in.
	 *
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return $this->getCurrentUser() !== null;
	}

	/**
	 * Get current user id.
	 *
	 * @return int
	 */
	public function getCurrentUserId()
	{
		$user = $this->getCurrentUser();
		return $user ? $user->getid() : 0;
	}

	/**
	 * Get the current user.
	 *
	 * @return User|null
	 */
	public function getCurrentUser()
	{
		return $this->session->get('user');
	}

	/**
	 * Set the current user.
	 *
	 * @param User $user
	 * @return void
	 */
	public function setCurrentUser($user)
	{
		$this->session
			->set('user', $user);
	}

	/**
	 * Clear the current user.
	 *
	 * @return void
	 */
	public function clearCurrentUser()
	{
		$this->session
			->set('user', null);
	}

	/**
	 * Check if route is allowed to accessed.
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function routeValidation(Request $request)
	{
		$routeName = $this->getCurrentRoute($request);
		if ($routeName && in_array($routeName, $this->authExcludeRoutes())) {
			return true;
		}
		return $this->isLoggedIn();
	}

	/**
	 * Get the current route.
	 *
	 * @param Request $request
	 * @return string|null
	 */
	protected function getCurrentRoute(Request $request)
	{
		return $request->attributes->get('_route');
	}

	/**
	 * List of allowed routes without login.
	 *
	 * @return string[]
	 */
	protected function authExcludeRoutes()
	{
		return ['home', 'login'];
	}
}