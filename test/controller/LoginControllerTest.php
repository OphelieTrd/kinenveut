<?php

use PHPUnit\Framework\TestCase;

include_once 'src/tools.php';
include_once 'src/parameters.php';

class LoginControllerTest extends TestCase
{
  /**
   * @test
   * @covers LoginController
   */
  public function indexTest()
  {
    $loginController = new LoginController();
    $data = $loginController->index();

    $this->assertSame('render', $data[0]);
    $this->assertSame('index', $data[1]);
  }

  /**
   * @test
   * @covers LoginController
   */
  public function loginTest()
  {
    global $parameters;
    $parameters = ['email' => 'test@kinenveut.fr', 'password' => 'password'];
    $loginController = new LoginController();
    $expectedId = 42;
    $expectedIsAdmin = true;
    $userTest1 = new UserModel();
    $userTest1
      ->setId($expectedId)
      ->setIsAdmin($expectedIsAdmin)
      ->setIsAuthorised(1);
    $userTest2 = null;

    $userBoMock = $this->createPartialMock(UserBoImpl::class, ['selectUserByEmailAndPassword']);
    $userBoMock->method('selectUserByEmailAndPassword')->will($this->onConsecutiveCalls($userTest1, $userTest2));

    $app_BoFactoryMock = $this->createPartialMock(App_BoFactory::class, ['getUserBo']);
    $app_BoFactoryMock->method('getUserBo')->willReturn($userBoMock);
    App_BoFactory::setFactory($app_BoFactoryMock);

    $data = $loginController->login();
    $this->assertSame('redirect', $data[0]);
    $this->assertSame('?r=home', $data[1]);
    $this->assertSame($expectedId, $_SESSION['userId']);
    $this->assertSame($expectedIsAdmin, $_SESSION['isAdmin']);

    unset($_SESSION['userId'], $_SESSION['isAdmin']);

    $data = $loginController->login();
    $this->assertSame('render', $data[0]);
    $this->assertSame('index', $data[1]);
    $this->assertSame('Identifiants incorrects', $data[2]['errors']['wrongIdentifiers']);
    $this->assertNull(isset($_SESSION['userId']) ? 1 : null);
    $this->assertNull(isset($_SESSION['isAdmin']) ? 1 : null);

    unset($_SESSION['userId'], $_SESSION['isAdmin']);
  }
}
