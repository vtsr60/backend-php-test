<?php

namespace Controller;

use Service\MessageService;
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
	 * @param $messageService
	 */
	public function __construct($app, $messageService)
	{
		parent::__construct($app);
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
	 * @return string
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function index($id = null)
	{
		if ($id) {
			$todo = $this->todoService->getTodoForCurrentUser($id);
			if ($todo) {
				return $this->getTwig()->render('todo.html', [
					'todo' => $todo
				]);
			}
			throw new NotFoundHttpException("Requested todo was not found.");
		}
		return $this->getTwig()->render('todos.html', [
			'todos' => $this->todoService->getTodosForCurrentUser()
		]);
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

}
