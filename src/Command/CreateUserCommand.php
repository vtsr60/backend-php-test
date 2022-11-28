<?php

namespace Command;

use Doctrine\ORM\EntityManager;
use Entity\User;
use Silex\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Create User Command Class
 * Console command to create user.
 */
class CreateUserCommand
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
		if (count($params) < 2) {
			$output->writeln("Please provide username and password in the arguments");
			return;
		}
		$newUser = new User();
		$newUser->setusername(trim($params[0]));
		$newUser->setpassword(password_hash(trim($params[1]), PASSWORD_DEFAULT));
		$this->entityManager->persist($newUser);
		$this->entityManager->flush();
		$output->writeln("User " . $newUser->getusername() . " was created.");
		$output->writeln("DONE");
	}
}