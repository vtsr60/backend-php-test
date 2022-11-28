<?php

namespace Service;


use Entity\User;
use Exception\ValidationException;

/**
 * User Service Class
 * Handle all the user related actions.
 */
class UserService extends EntityService
{
	/**
	 * Set to user entity class
	 *
	 * @var string
	 */
	protected $entityClass = User::class;

	/**
	 * Login validation user based on give username and password.
	 *
	 * @param $username
	 * @param $password
	 * @return bool
	 * @throws ValidationException
	 */
	public function login($username, $password)
	{
		if ($username && !empty($username)
			&& $password && !empty($password)) {
			$user = $this->findOneBy([
				'username' => $username
			]);

			if ($user
				&& $this->getAuthService()->validatePassword($user, $password)) {
				$this->getAuthService()
					->setCurrentUser($user);
				return true;
			}
			throw new ValidationException(
				"Please enter valid username and password.",
				"login",
				'/login');
		}
		throw new ValidationException(
			"Please enter both username and password.",
			"login",
			'/login');
	}

	/**
	 * Logut the current user.
	 *
	 * @return bool
	 */
	public function logout()
	{
		$this->getAuthService()
			->clearCurrentUser();
		return true;
	}
}
