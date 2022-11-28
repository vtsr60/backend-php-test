<?php

namespace Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;

/**
 * Base Entity Service Class
 * Handle common ORM actions.
 *
 */
class EntityService
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var AuthService
	 */
	private $authService;

	/**
	 * Entity class name should be set by the child classes.
	 *
	 * @var string
	 */
	protected $entityClass;

	/**
	 * @param $entityManager
	 * @param $authService
	 */
	public function __construct($entityManager, $authService)
	{
		$this->entityManager = $entityManager;
		$this->authService = $authService;
	}

	/**
	 * Get authentication service.
	 *
	 * @return AuthService
	 */
	protected function getAuthService()
	{
		return $this->authService;
	}

	/**
	 * Get entity repository for the entity class.
	 *
	 * @return \Doctrine\ORM\EntityRepository
	 */
	private function getRepository()
	{
		return $this->entityManager
			->getRepository($this->entityClass);
	}

	/**
	 * Finds entities by a set of criteria.
	 *
	 * @param $criteria
	 * @return array
	 */
	protected function findBy($criteria = [])
	{
		return $this->getRepository()
			->findBy($criteria);
	}

	/**
	 * Finds a single entity by a set of criteria.
	 *
	 * @param $criteria
	 * @return object|null
	 */
	protected function findOneBy($criteria = [])
	{
		return $this->getRepository()
			->findOneBy($criteria);
	}

	/**
	 * Finds an entity by its id.
	 *
	 * @param $id
	 * @return object|null
	 */
	protected function findById($id)
	{
		return $this->getRepository()
			->find($id);
	}

	/**
	 * Save the given entity to database.
	 *
	 * @param Entity $entity
	 * @return void
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	protected function save($entity)
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
		return $entity;
	}

	/**
	 * Delete the given entity from database.
	 *
	 * @param Entity $entity
	 * @return void
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	protected function remove($entity)
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
	}

}