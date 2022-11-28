<?php

namespace Command;

use Doctrine\ORM\EntityManager;
use Entity\User;
use Silex\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * User Password Hash Command Class
 * Hash the password for all the user.
 */
class UserPasswordHashCommand
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(Application $app)
	{
		$this->entityManager = $app['orm.em'];
	}

	/**
	 * @param ConsoleOutput $output
	 * @return void
	 */
	public function run($output, $params = [])
	{
		$users = $this->entityManager
			->getRepository(User::class)
			->findAll();
		foreach ($users as $user) {
			$currentPassword = $user->getpassword();
			if (!preg_match('/^\$/', $currentPassword)) {
				$output->writeln("Hashing the password for user :: " . $user->getusername());
				$user->setpassword(password_hash($currentPassword, PASSWORD_DEFAULT));
				$this->entityManager->persist($user);
				$this->entityManager->flush();
			} else {
				$output->writeln("Password already hashed for user :: " . $user->getusername());
			}
		}
		$output->writeln("DONE");
	}
}