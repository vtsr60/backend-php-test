<?php

namespace Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\OrderBy;
use Helper\Constants;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
	 * @var ValidatorInterface
	 */
	private $validator;

	/**
	 * Entity class name should be set by the child classes.
	 *
	 * @var string
	 */
	protected $entityClass;

	/**
	 * @param $entityManager
	 * @param $authService
	 * @param $validator
	 */
	public function __construct($entityManager, $authService, $validator)
	{
		$this->entityManager = $entityManager;
		$this->authService = $authService;
		$this->validator = $validator;
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
	 * Paginated list of the entity.
	 *
	 * @param $criteria
	 * @param $page
	 * @param $sorts
	 * @param $limit
	 * @return array
	 * @throws Query\QueryException
	 */
	protected function findByPaginated($criteria = [], $page = 1, $sorts = [], $limit = Constants::PAGINATION_LIMIT)
	{
		$orderBy = new OrderBy('a.id', 'ASC');
		if ($sorts && count($sorts)) {
			$orderBy = new OrderBy();
			foreach ($sorts as $col => $order) {
				$orderBy->add("a.{$col}", $order);
			}
		}

		$query = $this->getRepository()
			->createQueryBuilder('a')
			->orderBy($orderBy);

		foreach ($criteria as $col => $value) {
			$query->where("a.$col = :$col")
				->setParameter($col, $value);
		}

		$offset = 0;
		if ($page > 0) {
			$offset = ($page - 1) * $limit;
		}

		$query->setMaxResults($limit)
			->setFirstResult($offset);

		$result = $query->getQuery()
			->getResult(Query::HYDRATE_SIMPLEOBJECT);
		$total = $this->findByPaginatedCount($criteria);
		$pageCount = ceil($total / $limit);
		$pages = range(1, $pageCount);
		return [
			'total' => $total,
			'pages' => $pages,
			'current' => $page,
			'items' => $result,
			'prev' => $page - 1 >= 1 ? $page - 1 : false,
			'next' => $page + 1 <= $pageCount ? $page + 1 : false,
			'paginated' => $total > $limit
		];
	}

	/**
	 * Count of the paginated entity list.
	 *
	 * @param $criteria
	 * @return float|int|mixed|string
	 * @throws Query\QueryException
	 */
	protected function findByPaginatedCount($criteria = [])
	{
		$query = $this->getRepository()
			->createQueryBuilder('a');

		foreach ($criteria as $col => $value) {
			$query->where("a.$col = :$col")
				->setParameter($col, $value);
		}

		$query->select('count(a.id)');

		return $query->getQuery()
			->getSingleScalarResult();;
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

	/**
	 * Validate the entity based on constraint given.
	 *
	 * @param $value
	 * @param $constraint
	 * @return string|null
	 */
	protected function validate($value, $constraint)
	{
		$errors = $this->validator
			->validate($value, $constraint);
		if ($errors && $errors->count()) {
			return $errors->get(0)
				->getMessage();
		}
		return null;
	}
}