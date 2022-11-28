<?php

namespace Controller;

use Doctrine\ORM\EntityManager;
use Service\AuthService;
use Service\CSRFTokenService;
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
	 * @var CSRFTokenService
	 */
	private $crsfTokenService;


	/**
	 * @param Application $app
	 */
	public function __construct($app, $responseService, $crsfTokenService)
	{
		$this->app = $app;
		$this->responseService = $responseService;
		$this->crsfTokenService = $crsfTokenService;
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
	 * Regenerate CSRF Token
	 *
	 * @return void
	 */
	public function regenerateCSRFToken()
	{
		$this->responseService->setCSRFToken(
			$this->crsfTokenService->regenerateToken()
		);
	}

	/**
	 * Validate the CSRF Token.
	 *
	 * @param $request
	 * @return bool
	 */
	public function validateCSRFToken($request)
	{
		if ($token = $request->get('CSRFToken')) {
			return $this->crsfTokenService
				->validateToken($token);
		}
		return false;
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
			->redirect($path);
	}

}
