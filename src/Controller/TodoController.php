<?php

namespace Controller;

use Service\MessageService;
use Service\ResponseService;
use Service\TodoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Todo Controller Class
 * Handle routes related to Todo action.
 */
class TodoController extends BaseController
{

	/**
	 * @var TodoService
	 */
	private $todoService;

	/**
	 * @var MessageService
	 */
	private $messageService;

	/**
	 * Setup the todo service
	 *
	 * @param $app
	 * @param $responseService
	 * @param $crsfTokenService
	 * @param $messageService
	 */
	public function __construct($app, $responseService, $crsfTokenService, $messageService)
	{
		parent::__construct($app, $responseService, $crsfTokenService);
		$this->todoService = new TodoService(
			$this->getEntityManager(),
			$this->getAuthService(),
			$this->getValidator()
		);
		$this->messageService = $messageService;
	}

	/**
	 * Handle todo route.
	 *
	 * @param $id
	 * @param $format
	 * @param Request $request
	 * @return string|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function index($id = null, $format = ResponseService::OUTPUT_HTML, Request $request)
	{
		$this->regenerateCSRFToken();

		if ($id) {
			$todo = $this->todoService
				->getTodoForCurrentUser($id);
			if ($todo) {
				return $this->sendOutput($todo, 'todo.html', $format);
			}
			throw new NotFoundHttpException("Requested todo was not found.");
		}
		return $this->sendOutput([
			'todos' => $this->todoService
				->getTodosForCurrentUser(
					$request->get('page', 1)
				)
		], 'todos.html', $format);
	}

	/**
	 * Handle todo.add route.
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception\ValidationException
	 */
	public function add(Request $request)
	{
		if (!$this->validateCSRFToken($request)) {
			throw new \Exception("Form session has expired, Please try again.");
		}

		$addedTodo = $this->todoService
			->addTodo($request->get('description'));
		if ($addedTodo) {
			$this->messageService->add(
				'todo.msg',
				"Todo '{$addedTodo->getdescription()}' was added.");
			return $this->redirect('/todo');
		}
		throw new \Exception("Failed to add todo.");
	}

	/**
	 * Handle todo.del route.
	 *
	 * @param $id
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function delete($id, Request $request)
	{
		if (!$this->validateCSRFToken($request)) {
			throw new \Exception("Form session has expired, Please try again.");
		}

		$deletedTodo = $this->todoService
			->delete($id);
		if ($deletedTodo) {
			$this->messageService->add(
				'todo.msg',
				"Todo '{$deletedTodo->getdescription()}' was deleted.");
			return $this->redirect('/todo');
		}
		throw new \Exception("Failed to delete todo.");
	}

	/**
	 * Handle todo.completed route.
	 *
	 * @param $id
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function completed($id, Request $request)
	{
		if (!$this->validateCSRFToken($request)) {
			throw new \Exception("Form session has expired, Please try again.");
		}

		$todo = $this->todoService
			->completed($id);
		if ($todo) {
			$this->messageService->add(
				'todo.msg',
				"Todo '{$todo->getdescription()}' was marked completed.");
			$redirectPath = '/todo';
			if ($referer = $request->headers->get('referer')) {
				$redirectPath = $referer;
			}
			return $this->redirect($redirectPath);
		}

		throw new \Exception("Failed to mark todo as completed.");
	}
}
