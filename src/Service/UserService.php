<?php

namespace Service;

use Entity\User;

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
		}
		return false;
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
