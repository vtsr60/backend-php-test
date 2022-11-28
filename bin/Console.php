<?php

use Command\CreateUserCommand;
use Command\UserPasswordHashCommand;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
$app->boot();

$commands = [
	"create.user" => CreateUserCommand::class,
	"hash.user.password" => UserPasswordHashCommand::class
];

$output = new ConsoleOutput();

if (count($argv) >= 2 && isset($commands[$argv[1]])) {
	$command = new $commands[$argv[1]]($app);
	$command->run($output, array_slice($argv, 2));
	exit();
} elseif (count($argv) >= 2) {
	$output->writeln("{$argv[1]} is not a valid command.");
	exit(1);
}

$output->writeln("Allowed commands");
foreach ($commands as $command => $class) {
	$output->writeln("\t - $command");
}

exit();