<?php

namespace Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Twig\Environment;

/**
 * Response Service Class
 * Handle the response of all request
 */
class ResponseService
{
	const OUTPUT_HTML = 'html';
	const OUTPUT_JSON = 'json';

	/**
	 * @var Environment
	 */
	private $twig;

	public function __construct($twig)
	{
		$this->twig = $twig;
	}

	/**
	 * Send output to browser based on the format.
	 *
	 * @param $data
	 * @param $template
	 * @param $format
	 * @return string|JsonResponse
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function output($data, $template, $format = self::OUTPUT_HTML)
	{
		if ($format === self::OUTPUT_JSON) {
			return new JsonResponse($data);
		}
		if (is_object($data)) {
			$data = json_decode(json_encode($data), true);
		}

		return $this->twig->render($template, $data);
	}
}