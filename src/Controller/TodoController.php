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
	 * @param $messageService
	 */
	public function __construct($app, $responseService, $messageService)
	{
		parent::__construct($app, $responseService);
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
		if ($id) {
			$todo = $this->todoService
				->getTodoForCurrentUser($id);
			if ($todo) {
				return $this->sendOutput($todo, 'todo.html', $format);
			}
			throw new NotFoundHttpException("Requested todo was not found.");
		}
		return $this->sendOutput([
			'todos' => $this->todoService->getTodosForCurrentUser()
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
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function delete($id)
	{
		$deletedTodo = $this->todoService
			->delete($id);
		if ($deletedTodo) {
			$this->messageService->add(
				'todo.msg',
				"Todo '{$deletedTodo->getdescription()}' was removed.");
			return $this->redirect('/todo');
		}
		throw new \Exception("Failed to remove todo.");
	}

	public function completed($id, Request $request)
	{
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
