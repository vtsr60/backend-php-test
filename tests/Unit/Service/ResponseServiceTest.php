<?php

namespace Unit\Service;


use PHPUnit\Framework\TestCase;
use Service\CSRFokenService;
use Service\ResponseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;

class ResponseServiceTest extends TestCase
{
	/**
	 * @var ResponseService
	 */
	private $service;

	/**
	 * @var Environment
	 */
	private $mockTwig;


	protected function setUp()
	{
		parent::setUp();
		$this->mockTwig = $this->getMockBuilder(Environment::class)
			->disableOriginalConstructor()
			->setMethods(['render'])
			->getMock();
		$this->service = $this->getMockBuilder(ResponseService::class)
			->setConstructorArgs([$this->mockTwig])
			->setMethods(['getCSRFToken'])
			->getMock();
	}

	protected function tearDown()
	{
		parent::tearDown();
		$this->mockTwig = null;
		$this->service = null;
	}

	public function testOutputHTMLWhenNoFormatArguments()
	{
		$crsfToken = 'randomToken';
		$data = ['CSRFToken' => $crsfToken];
		$this->mockTwig->expects($this->once())
			->method('render')
			->with('template', $data)
			->willReturn('renderResponse');
		$this->service->expects($this->once())
			->method('getCSRFToken')
			->willReturn($crsfToken);

		$this->assertEquals(
			'renderResponse',
			$this->service->output($data, 'template'));
	}

	public function testOutputHTMLWhenHTMLFormatArguments()
	{
		$crsfToken = 'randomToken';
		$data = ['CSRFToken' => $crsfToken];
		$this->mockTwig->expects($this->once())
			->method('render')
			->with('template', $data)
			->willReturn('renderResponse');
		$this->service->expects($this->once())
			->method('getCSRFToken')
			->willReturn($crsfToken);

		$this->assertEquals(
			'renderResponse',
			$this->service->output($data, 'template', ResponseService::OUTPUT_HTML));
	}

	public function testOutputHTMLWhenJSONFormatArguments()
	{
		$data = ["id" => 1234];
		$response = $this->service->output($data, 'template', ResponseService::OUTPUT_JSON);
		$this->assertInstanceOf(
			JsonResponse::class,
			$response);
		$this->assertEquals('{"id":1234}', $response->getContent());
	}

}