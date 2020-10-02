<?php

use PHPUnit\Framework\TestCase;

include_once 'src/tools.php';

class UserBoTest extends TestCase
{
  /** @before*/
  protected function setUp() : void
  {
    parent::setUp();
    App_BoFactory::setFactory(new App_BoFactory());
  }

  /**
   * @test
   * @covers
  */
  public function insertUserTest() : void
  {
    $expectedUserId = 42;
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userMock = $this->createPartialMock(UserModel::class, []);
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['insertUser']);
    $userDaoImpMock->method('insertUser')->willReturn($expectedUserId);
    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $userId = $userBo->insertUser($userMock);

    $this->assertSame($expectedUserId, $userId);
  }

  /**
   * @test
   * @covers
  */
  public function deleteUserTest() : void
  {
    $expectedSuccess = true;
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['deleteUser']);
    $userDaoImpMock->method('deleteUser')->willReturn($expectedSuccess);
    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $success = $userBo->deleteUser(42);

    $this->assertSame($expectedSuccess, $success);
  }

  /**
   * @test
   * @covers
  */
  public function selectUserByEmailTest() : void
  {
    $expectedUser = new UserModel();
    $expectedUser
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(2000 - 01 - 13)
      ->setEmail('Francis.Dupont@gmail.com')
      ->setIsAdmin('false');
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['selectUserByEmail']);
    $userDaoImpMock->method('selectUserByEmail')->willReturn($expectedUser);
    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $user = $userBo->selectUserByEmail('Francis.Dupont@gmail.com');

    $this->assertSame($expectedUser, $user);
  }

  /**
   * @test
   * @covers
  */
  public function selectUserByEmailAndPasswordTest() : void
  {
    $expectedUser = new UserModel();
    $expectedUser
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(2000 - 01 - 13)
      ->setEmail('Francis.Dupont@gmail.com')
      ->setIsAdmin('false');
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['selectUserByEmailAndPassword']);
    $userDaoImpMock->method('selectUserByEmailAndPassword')->willReturn($expectedUser);
    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $user = $userBo->selectUserByEmailAndPassword('Francis.Dupont@gmail.com', 'password');

    $this->assertSame($expectedUser, $user);
  }
}
