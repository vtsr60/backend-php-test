<?php

namespace Service;


use Entity\Todo;
use Exception\ValidationException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Todo Service Class
 * Handle all todo related actions.
 */
class TodoService extends EntityService
{
	/**
	 * Set to todo entity class
	 * @var string
	 */
	protected $entityClass = Todo::class;

	/**
	 * Get list of todos for current logged in user.
	 *
	 * @param $uri
	 * @param $page
	 * @return array
	 */
	public function getTodosForCurrentUser()
	{
		return $this->findBy([
			'user_id' => $this->getAuthService()
				->getCurrentUserId()
		]);

	}

	/**
	 * Get todo by id for the current logged in user.
	 *
	 * @param $id
	 * @return object|null
	 */
	public function getTodoForCurrentUser($id)
	{
		return $this->findOneBy([
			'id' => $id,
			'user_id' => $this->getAuthService()
				->getCurrentUserId()
		]);
	}

	/**
	 * Add todo entity for the current logged in user.
	 *
	 * @param $description
	 * @return \Doctrine\ORM\Mapping\Entity|null
	 * @throws ValidationException
	 */
	public function addTodo($description)
	{
		$error = $this->validate($description, new Assert\NotBlank());
		if ($error) {
			throw new ValidationException($error, 'add.todo');
		}
		try {
			$newTodo = new Todo();
			$newTodo->setuser_id($this->getAuthService()->getCurrentUserId())
				->setdescription($description);
			return $this->save($newTodo);
		} catch (\Exception $e) {
		}
		throw new \Exception('Unexpected error has happened. Failed to add new todo.');
	}

	/**
	 * Delete todo entity for the current logged in user.
	 *
	 * @param $id
	 * @return object|null
	 * @throws \Exception
	 */
	public function delete($id)
	{
		try {
			$todo = $this->getTodoForCurrentUser($id);
			$this->remove($todo);
			return $todo;
		} catch (\Exception $e) {
		}
		throw new \Exception('Unexpected error has happened. Failed to delete todo.');
	}

}