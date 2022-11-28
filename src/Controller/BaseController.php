<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Service\AuthService;
use Service\ResponseService;
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
	 * @var ResponseService
	 */
	private $responseService;

	/**
	 * @param Application $app
	 */
	public function __construct($app, $responseService)
	{
		$this->app = $app;
		$this->responseService = $responseService;
	}

	/**
	 * Send response for the request
	 *
	 * @param ...$params
	 * @return string|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function sendOutput(...$params)
	{
		return $this->responseService->output(...$params);
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
	 * Get the Validator instance/
	 *
	 * @return mixed
	 */
	public function getValidator()
	{
		return $this->app['validator'];
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
