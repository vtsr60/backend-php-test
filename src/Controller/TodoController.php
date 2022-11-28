<?php

namespace Controller;

use Service\TodoService;
use Symfony\Component\HttpFoundation\Request;

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
	 * Setup the todo service
	 *
	 * @override
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		$this->todoService = new TodoService(
			$this->getEntityManager(),
			$this->getAuthService()
		);
	}

	/**
	 * Handle todo route.
	 *
	 * @param $id
	 * @return string
	 */
	public function index($id = null)
	{
		if ($id) {
			$todo = $this->todoService->getTodoForCurrentUser($id);
			if ($todo) {
				return $this->getTwig()
					->render('todo.html', [
						'todo' => $todo
					]);
			}
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
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function add(Request $request)
	{
		$this->todoService
			->addTodo($request->get('description'));
		return $this->redirect('/todo');
	}

	/**
	 * Handle todo.del route.
	 *
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function delete($id, Request $request)
	{
		$this->todoService
			->delete($id);
		return $this->redirect('/todo');
	}

}
