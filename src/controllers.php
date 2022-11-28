<?php

use Controller\UserController;
use Controller\HomeController;
use Controller\TodoController;
use Service\AuthService;
use Service\CSRFTokenService;
use Service\MessageService;
use Service\ResponseService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Setup required services and components.
 */
$app['message.service'] = function () use ($app) {
	return new MessageService($app, $app['session']->getFlashBag());
};

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
	$twig->addGlobal('user', $app['auth.service']->getCurrentUser());
	$twig->addGlobal('messages', $app['message.service']);
	return $twig;
}));

$app['auth.service'] = function () use ($app) {
	return new AuthService($app['session']);
};

$app['crsf.service'] = function () use ($app) {
	return new CSRFTokenService($app['session']);
};

$app['response.service'] = function () use ($app) {
	return new ResponseService($app['twig']);
};

/**
 * Setup controllers.
 */
$app['home.controller'] = function () use ($app) {
	return new HomeController($app, $app['response.service'], $app['crsf.service']);
};

$app['todos.controller'] = function () use ($app) {
	return new TodoController($app, $app['response.service'], $app['crsf.service'], $app['message.service']);
};

$app['user.controller'] = function () use ($app) {
	return new UserController($app, $app['response.service'], $app['crsf.service'], $app['message.service']);
};

/**
 * Add authentication middleware.
 */
$app->before(function (Request $request) use ($app) {
	if ($app['auth.service']->routeValidation($request)) {
		// Allowed access
		return;
	}
	return $app->redirect('/login');
});

/**
 * Add error handling service.
 */
$app->error('message.service:handleError');

/**
 * Setup routes
 */
$app->get('/', 'home.controller:index')
	->bind('home');

$app->match('/login', 'user.controller:login')
	->bind('login');
$app->get('/logout', 'user.controller:logout')
	->bind('logout');

$app->get('/todo/{id}/{format}', 'todos.controller:index')
	->value('id', null)
	->assert('id', '\d+')
	->value('format', null)
	->assert('format', 'json')
	->bind('todo');
$app->get('/todo/json', 'todos.controller:index')
	->value('id', null)
	->value('format', 'json')
	->bind('todo.json');
$app->post('/todo/add', 'todos.controller:add')
	->bind('todo.add');
$app->post('/todo/delete/{id}', 'todos.controller:delete')
	->bind('todo.delete');
$app->post('/todo/completed/{id}', 'todos.controller:completed')
	->bind('todo.completed');
