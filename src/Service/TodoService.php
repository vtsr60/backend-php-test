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
	 * @param $page
	 * @return array
	 */
	public function getTodosForCurrentUser($page = 1)
	{
		return $this->findByPaginated([
			'user_id' => $this->getAuthService()
				->getCurrentUserId()
		], $page, [
			'completed' => 'ASC',
			'id' => 'ASC'
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
		$description = trim($description);

		$error = $this->validate($description, [
			new Assert\NotBlank([
				'message' => 'Description should not be blank.',
			]),
			new Assert\Length([
				'max' => 255,
				'minMessage' => 'Description should not be blank.',
				'maxMessage' => 'Description cannot be longer than 255 characters',
			])
		]);
		if ($error) {
			throw new ValidationException($error, 'add.todo');
		}

		try {
			$newTodo = new Todo();
			$newTodo->setuser_id($this->getAuthService()->getCurrentUserId())
				->setdescription($description)
				->setcompleted(false);
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

	/**
	 * Make the given todo completed.
	 *
	 * @param $id
	 * @return \Doctrine\ORM\Mapping\Entity|void|null
	 * @throws \Exception
	 */
	public function completed($id)
	{
		try {
			$todo = $this->getTodoForCurrentUser($id);
			$todo->setcompleted(true);
			$todo->setcompleted_on(new \DateTime("now"));
			return $this->save($todo);
		} catch (\Exception $e) {
		}
		throw new \Exception('Unexpected error has happened. Failed to mark completed todo.');
	}

}