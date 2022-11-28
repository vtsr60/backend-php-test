<?php

namespace Unit\Service;


use PHPUnit\Framework\TestCase;
use Service\CSRFTokenService;
use Symfony\Component\HttpFoundation\Session\Session;

class CSRFTokenServiceTest extends TestCase
{
	/**
	 * @var CSRFTokenService
	 */
	private $service;

	/**
	 * @var Session
	 */
	private $mockSession;

	protected function setUp()
	{
		parent::setUp();
		$this->mockSession = $this->getMockBuilder(Session::class)
			->disableOriginalConstructor()
			->setMethods(['get', 'set'])
			->getMock();
		$this->service = new CSRFTokenService($this->mockSession);
	}

	protected function tearDown()
	{
		parent::tearDown();
		$this->mockSession = null;
		$this->service = null;
	}

	public function testValidateTokenFalseWithNullToken()
	{
		$this->service = $this->getMockBuilder(CSRFTokenService::class)
			->disableOriginalConstructor()
			->setMethods(['getToken'])
			->getMock();

		$this->service->expects($this->once())
			->method('getToken')
			->willReturn('randomToken');

		$this->assertFalse($this->service->validateToken(null));

	}

	public function testValidateTokenFalseWithInValidToken()
	{
		$this->service = $this->getMockBuilder(CSRFTokenService::class)
			->disableOriginalConstructor()
			->setMethods(['getToken'])
			->getMock();

		$this->service->expects($this->once())
			->method('getToken')
			->willReturn('randomToken');

		$this->assertFalse($this->service->validateToken('anotherRandomToken'));

	}

	public function testValidateTokenFalseWithValidToken()
	{
		$this->service = $this->getMockBuilder(CSRFTokenService::class)
			->disableOriginalConstructor()
			->setMethods(['getToken'])
			->getMock();

		$this->service->expects($this->once())
			->method('getToken')
			->willReturn('randomToken');

		$this->assertTrue($this->service->validateToken('randomToken'));

	}

}