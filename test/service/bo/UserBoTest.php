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
   * @covers UserBoImpl
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
   * @covers UserBoImpl
  */
  public function selectUserByEmailTest() : void
  {
    $expectedUser = new UserModel();
    $expectedUser
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(new DateTime('2000-01-13'))
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
   * @covers UserBoImpl
  */
  public function selectUserByEmailAndPasswordTest() : void
  {
    $expectedUser = new UserModel();
    $expectedUser
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(new DateTime('2000-01-13'))
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

  /**
   * @test
   * @covers UserBoImpl
   */
  public function selectUserByUserIdTest() : void
  {
    $idTest = 42;
    $expectedUser = new UserModel();
    $expectedUser
            ->setId($idTest)
            ->setFirstName('Francis')
            ->setLastName('Dupont')
            ->setBirthDate(new DateTime('2000-01-13'))
            ->setEmail('Francis.Dupont@gmail.com')
            ->setIsAdmin('false');
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['selectUserByUserId']);
    $userDaoImpMock->method('selectUserByUserId')->willReturn($expectedUser);

    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $user = $userBo->selectUserByUserId($idTest);

    $this->assertSame($expectedUser, $user);
  }

  /**
   * @test
   * @covers UserBoImpl
   */
  public function updateUserTest() : void
  {
    $expectedSuccess = true;
    $user = new UserModel();
    $user
            ->setId(42)
            ->setFirstName('Francis')
            ->setLastName('Dupont')
            ->setBirthDate(new DateTime('2000-01-13'))
            ->setEmail('Francis.Dupont@gmail.com')
            ->setIsAdmin('false')
            ->setIsAuthorised(6);
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['updateUser']);
    $userDaoImpMock->method('updateUser')->willReturn(true);
    $auctionAccessStateDaoImplMock = $this->createPartialMock(AuctionAccessStateDaoImpl::class, ['cancelAuctionAccessStateByUserId']);
    $auctionAccessStateDaoImplMock->method('cancelAuctionAccessStateByUserId')->willReturn(true);
    $bidHistoryDaoImplMock = $this->createPartialMock(BidHistoryDaoImpl::class, ['deleteCurrentBidsByBidderId']);
    $bidHistoryDaoImplMock->method('deleteCurrentBidsByBidderId')->willReturn(true);
    $auctionDaoImplMock = $this->createPartialMock(AuctionDaoImpl::class, ['cancelOnlineAuctionsBySellerId']);
    $auctionDaoImplMock->method('cancelOnlineAuctionsBySellerId')->willReturn(true);

    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao', 'getAuctionAccessStateDao', 'getBidHistoryDao', 'getAuctionDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    $app_DaoFactoryMock->method('getAuctionAccessStateDao')->willReturn($auctionAccessStateDaoImplMock);
    $app_DaoFactoryMock->method('getBidHistoryDao')->willReturn($bidHistoryDaoImplMock);
    $app_DaoFactoryMock->method('getAuctionDao')->willReturn($auctionDaoImplMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $success = $userBo->updateUser($user);

    $this->assertSame($expectedSuccess, $success);
  }

  /**
   * @test
   * @covers UserBoImpl
   */
  public function updateUserIsAuthorisedTest() : void
  {
    $expectedSuccess = true;
    $user = new UserModel();
    $user
            ->setId(42)
            ->setFirstName('Francis')
            ->setLastName('Dupont')
            ->setBirthDate(new DateTime('2000-01-13'))
            ->setEmail('Francis.Dupont@gmail.com')
            ->setIsAdmin('false');
    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['updateUserIsAuthorised']);
    $userDaoImpMock->method('updateUserIsAuthorised')->willReturn(true);

    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $success = $userBo->updateUserIsAuthorised($user);

    $this->assertSame($expectedSuccess, $success);
  }

  /**
   * @test
   * @covers UserBoImpl
   */
  public function selectUsersByStateTest() : void
  {
    $expecteduser = new UserModel();
    $expecteduser
            ->setId(42)
            ->setFirstName('Francis')
            ->setLastName('Dupont')
            ->setBirthDate(new DateTime('2000-01-13'))
            ->setEmail('Francis.Dupont@gmail.com')
            ->setIsAdmin('false');

    $expecteduserList = [$expecteduser];

    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['selectUsersByState']);
    $userDaoImpMock->method('selectUsersByState')->willReturn($expecteduserList);

    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $userList = $userBo->selectUsersByState(0);

    $this->assertSame($expecteduserList, $userList);
  }

  /**
   * @test
   * @covers UserBoImpl
   */
  public function selectAllUserExceptState0Test() : void
  {
    $expecteduser = new UserModel();
    $expecteduser
      ->setId(42)
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(new DateTime('2000-01-13'))
      ->setEmail('Francis.Dupont@gmail.com')
      ->setIsAdmin('false');

    $expecteduser2 = new UserModel();
    $expecteduser2
      ->setId(43)
      ->setFirstName('Francoise')
      ->setLastName('Dupond')
      ->setBirthDate(new DateTime('2000-01-13'))
      ->setEmail('Francoise.Dupond@gmail.com')
      ->setIsAdmin('false')
      ->setIsAuthorised(1);

    $expecteduserList = [$expecteduser, $expecteduser2];

    $userBo = App_BoFactory::getFactory()->getUserBo();
    $userDaoImpMock = $this->createPartialMock(UserDaoImpl::class, ['selectAllUserExceptState0']);
    $userDaoImpMock->method('selectAllUserExceptState0')->willReturn([$expecteduser2]);

    $app_DaoFactoryMock = $this->createPartialMock(App_DaoFactory::class, ['getUserDao']);
    $app_DaoFactoryMock->method('getUserDao')->willReturn($userDaoImpMock);
    App_DaoFactory::setFactory($app_DaoFactoryMock);

    $userList = $userBo->selectAllUserExceptState0();

    $this->assertSame([$expecteduser2], $userList);
  }
}
