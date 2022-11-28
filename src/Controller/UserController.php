<?php

namespace Controller;

use Service\UserService;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * User Controller Class
 * Handle routes related to user.
 */
class UserController extends BaseController
{
	/**
	 * @var UserService
	 */
	private $userService;

	/**
	 * @param Application $app
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		$this->userService = new UserService(
			$this->getEntityManager(),
			$this->getAuthService()
		);
	}

	/**
	 * Handle login route.
	 *
	 * @param Request $request
	 * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function login(Request $request)
	{
		if ('POST' === $request->getMethod() &&
			$this->userService->login(
				$request->get('username'),
				$request->get('password')
			)) {
			return $this->redirect('/todo');
		}

		return $this->getTwig()->render('login.html', []);
	}

	/**
	 * Handle logout route.
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function logout()
	{
		$this->userService->logout();
		return $this->redirect('/');

	}
}