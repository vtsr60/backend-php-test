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
	 * @return string
	 */
	public function index()
	{
		return $this->getTwig()->render('index.html', [
			'readme' => file_get_contents('README.md'),
		]);
	}
}