<?php

namespace Controller;

/**
 * Home Controller class
 * Handles home route.
 */
class HomeController extends BaseController
{
	/**
	 * Handle home route - loads readme file
	 *
	 * @return string|\Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function index()
	{
		return $this->sendOutput([
			'readme' => file_get_contents('README.md'),
		], 'index.html');
	}
}