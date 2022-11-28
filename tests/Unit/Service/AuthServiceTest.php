<?php

namespace Unit\Service;


use Entity\User;
use PHPUnit\Framework\TestCase;
use Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthServiceTest extends TestCase
{
	/**
	 * @var AuthService
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
		$this->service = new AuthService($this->mockSession);
	}

	protected function tearDown()
	{
		parent::tearDown();
		$this->mockSession = null;
		$this->service = null;
	}

	public function testValidatePasswordFalseWithNullPassword()
	{
		$mockUser = $this->getMockBuilder(User::class)
			->disableOriginalConstructor()
			->setMethods(['getpassword'])
			->getMock();
		$mockUser->expects($this->once())
			->method('getpassword')
			->willReturn(null);

		$this->assertFalse($this->service->validatePassword($mockUser, 'differentpassword'));
	}


	public function testValidatePasswordFalseWithInValidPassword()
	{
		$mockUser = $this->getMockBuilder(User::class)
			->disableOriginalConstructor()
			->setMethods(['getpassword'])
			->getMock();
		$mockUser->expects($this->once())
			->method('getpassword')
			->willReturn('wrongpassword');

		$this->assertFalse($this->service->validatePassword($mockUser, 'differentpassword'));
	}

	public function testValidatePasswordTrueWithValidPassword()
	{
		$password = 'samePassword';
		$mockUser = $this->getMockBuilder(User::class)
			->disableOriginalConstructor()
			->setMethods(['getpassword'])
			->getMock();
		$mockUser->expects($this->once())
			->method('getpassword')
			->willReturn($password);

		$this->assertTrue($this->service->validatePassword($mockUser, $password));
	}

	public function testGetCurrentUserIdIsZeroNoUser()
	{
		$this->assertEquals(0, $this->service->getCurrentUserId());
	}

	public function testGetCurrentUserIdCorrectWithUser()
	{
		$randomId = rand(1, 10000);
		$mockUser = $this->getMockBuilder(User::class)
			->disableOriginalConstructor()
			->setMethods(['getId'])
			->getMock();
		$mockUser->expects($this->any())
			->method('getId')
			->willReturn($randomId);
		$this->mockSession->expects($this->once())
			->method('get')
			->with('user')
			->willReturn($mockUser);

		$this->assertEquals($randomId, $this->service->getCurrentUserId());
	}

	public function testIsLoggedInFalseOnNoUser()
	{
		$this->assertFalse($this->service->isLoggedIn());
	}

	public function testIsLoggedInTrueWithUser()
	{
		$user = new User();
		$this->mockSession->expects($this->once())
			->method('set')
			->with('user', $user);
		$this->mockSession->expects($this->once())
			->method('get')
			->with('user')
			->willReturn($user);

		$this->service->setCurrentUser($user);
		$this->assertTrue($this->service->isLoggedIn());
	}

	public function testIsLoggedInFalseAfterUserCleared()
	{
		$user = new User();
		$this->mockSession->expects($this->exactly(2))
			->method('set')
			->withConsecutive(
				['user', $user],
				['user', null]
			);

		$this->mockSession->expects($this->once())
			->method('get')
			->with('user')
			->willReturn(null);

		$this->service->setCurrentUser($user);
		$this->service->clearCurrentUser();

		$this->assertFalse($this->service->isLoggedIn());
	}

	public function testRouteValidationFalseForNotLoggedUser()
	{
		$this->service = $this->getMockBuilder(AuthService::class)
			->disableOriginalConstructor()
			->setMethods(['authExcludeRoutes', 'getCurrentRoute', 'isLoggedIn'])
			->getMock();
		$dummyRoute = "dummy123";
		$this->service->expects($this->once())
			->method('authExcludeRoutes')
			->willReturn([]);
		$this->service->expects($this->once())
			->method('getCurrentRoute')
			->willReturn($dummyRoute);
		$this->service->expects($this->any())
			->method('isLoggedIn')
			->willReturn(false);

		$this->assertFalse($this->service->routeValidation(new Request()));
	}

	public function testRouteValidationTrueForLoggedUser()
	{
		$this->service = $this->getMockBuilder(AuthService::class)
			->disableOriginalConstructor()
			->setMethods(['authExcludeRoutes', 'getCurrentRoute', 'isLoggedIn'])
			->getMock();
		$dummyRoute = "dummy345";
		$this->service->expects($this->once())
			->method('authExcludeRoutes')
			->willReturn([]);
		$this->service->expects($this->once())
			->method('getCurrentRoute')
			->willReturn($dummyRoute);
		$this->service->expects($this->any())
			->method('isLoggedIn')
			->willReturn(true);

		$this->assertTrue($this->service->routeValidation(new Request()));
	}

	public function testRouteValidationTrueForAllowedRoutes()
	{
		$this->service = $this->getMockBuilder(AuthService::class)
			->disableOriginalConstructor()
			->setMethods(['authExcludeRoutes', 'getCurrentRoute', 'isLoggedIn'])
			->getMock();
		$dummyRoute = "dummy678";
		$this->service->expects($this->once())
			->method('authExcludeRoutes')
			->willReturn([$dummyRoute, "dummy123", "dummy456"]);
		$this->service->expects($this->once())
			->method('getCurrentRoute')
			->willReturn($dummyRoute);
		$this->service->expects($this->any())
			->method('isLoggedIn')
			->willReturn(false);

		$this->assertTrue($this->service->routeValidation(new Request()));
	}

}