<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Service\AuthService;
use Silex\Application;
use Twig\Environment;

/**
 * Base Controller Classes
 * Common class to hold controllers command methods and actions.
 */
class BaseController
{
	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @param Application $app
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Get the application object.
	 *
	 * @return Application
	 */
	protected function getApp()
	{
		return $this->app;
	}

	/**
	 * Get the twig engine.
	 *
	 * @return Environment
	 */
	protected function getTwig()
	{
		return $this->app['twig'];
	}

	/**
	 * Get the entity manager.
	 *
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		return $this->app['orm.em'];
	}

	/**
	 * Get the authentication service.
	 *
	 * @return AuthService
	 */
	protected function getAuthService()
	{
		return $this->app['auth.service'];
	}

	/**
	 * Redirect to specified path.
	 *
	 * @param $path
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	protected function redirect($path = '/')
	{
		return $this->getApp()
			->redirect('/todo');
	}

}
