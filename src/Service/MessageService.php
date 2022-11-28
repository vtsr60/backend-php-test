<?php

namespace Service;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Message Service Class
 * Handle the messaging and errors of the applications.
 */
class MessageService
{
	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var FlashBag
	 */
	private $flashBag;

	/**
	 * @param $app
	 * @param $flashBag
	 */
	public function __construct($app, $flashBag)
	{
		$this->app = $app;
		$this->flashBag = $flashBag;
	}

	/**
	 * Handle the all errors from the application.
	 *
	 * @param Exception $e
	 * @param $code
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
	 */
	public function handleError(Exception $e, $code)
	{
		$redirectPath = '/todo';
		switch (get_class($e)) {
			case Exception\ValidationException::class:
				$this->add(
					$e->getType(),
					$e->getMessage());
				if ($e->getRedirectPath()) {
					$redirectPath = $e->getRedirectPath();
				}
				break;
			case NotFoundHttpException::class:
				$this->add(
					'error',
					'The requested resource could not be found.',
					'404 Not Found');
				break;
			default:
				$this->add('error', $e->getMessage(), 'Something went wrong');
		}

		if ($this->getCurrentRequest()->getPathInfo() == $redirectPath) {
			return;
		}

		return $this->app
			->redirect($redirectPath);
	}

	/**
	 * Add flash bag message.
	 *
	 * @param $type
	 * @param $message
	 * @param $title
	 * @return null
	 */
	public function add($type, $message, $title = null)
	{
		return $this->flashBag->add(
			$type,
			array(
				'title' => $title,
				'message' => $message,
			)
		);
	}

	/**
	 * Get the message of type.
	 *
	 * @param $type
	 * @return array|mixed
	 */
	public function get($type)
	{
		return $this->flashBag->get($type);
	}

	/**
	 * Check if message of type present.
	 *
	 * @param $type
	 * @return bool
	 */
	public function hasMessage($type)
	{
		return $this->flashBag->has($type);
	}

	/**
	 * @return Request
	 */
	protected function getCurrentRequest()
	{
		return $this->app["request"];
	}

}