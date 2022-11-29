<?php

namespace Service;


use Entity\User;
use Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;

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
			if ($this->authentication($username, $password)) {
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

	/**
	 * Login using the basic auth request(For JSON API)
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function apiBasicAuthLogin(Request $request)
	{
		$basicAuthCode = $request->headers->get('Authorization', false);
		if ($basicAuthCode &&  preg_match('/^Basic /i', $basicAuthCode)) {
			$basicAuthCode = str_ireplace("Basic ","", $basicAuthCode);
			list($username, $password) = explode(':', base64_decode($basicAuthCode));
			if ($username && !empty($username)
				&& $password && !empty($password)) {
				return $this->authentication($username, $password);
			}
		}
		return false;
	}

	/**
	 * Authenticated user if valid username and password given.
	 *
	 * @param $username
	 * @param $password
	 * @return bool
	 */
	protected function authentication($username, $password)
	{
		$user = $this->findOneBy([
			'username' => $username
		]);

		if ($user && $this->getAuthService()->validatePassword($user, $password)) {
			$this->getAuthService()
				->setCurrentUser($user);
			return true;
		}

		return false;
	}
}
