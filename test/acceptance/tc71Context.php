<?php

use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc71Context implements Context
{
  /**
   * @When l'utilisateur tape le nom de son objet dans la barre de recherche
   */
  public function lutilisateurTapeLeNomDeSonObjetDansLaBarreDeRecherche()
  {
    $session = Universe::getUniverse()->getSession();
    $auction = Universe::getUniverse()->getAuction();

    $session->getPage()->find(
      'css',
      'input[name="searchInput"]'
    )->setValue($auction->getName());
  }

  /**
   * @Given L'utilisateur possède au moins une enchère
   */
  public function lutilisateurPossedeAuMoinsUneEnchere()
  {
    $session = Universe::getUniverse()->getSession();
    $user = Universe::getUniverse()->getUser();
    $auction = new AuctionModel();

    if ($user->getId() == null || $user->getId() < 1) {
      $userDao = App_DaoFactory::getFactory()->getUserDao();
      $user = $userDao->selectUserByEmail(Universe::getUniverse()->getUser()->getEmail());
      Universe::getUniverse()->getUser()->setId($user->getId());
      $user = Universe::getUniverse()->getUser();
    }

    $auction
          ->setName('Objet test')
          ->setDescription('Ceci est une enchère insérée lors de tests.')
          ->setBasePrice(3)
          ->setReservePrice(7)
          ->setDuration(7)
          ->setSellerId($user->getId())
          ->setPrivacyId(0)
          ->setCategoryId(1)
          ->setStartDate(new DateTime());

    Universe::getUniverse()->setAuction($auction);

    visitCreateAuction($session);
    createAuction($session, $auction);

    disconnect();

    /*Connection as Admin*/
    $userAdmin = new UserModel();
    $userAdmin
          ->setEmail('admin@kinenveut.fr')
          ->setPassword('password');

    Universe::getUniverse()->setUser2($userAdmin);

    connect($session, $userAdmin);

    visitAuctionManagement($session);

    //Todo : use the name to find the button :)
    $auctionDao = App_DaoFactory::getFactory()->getAuctionDao();
    $userAuctions = $auctionDao->selectAllAuctionsBySellerId($user->getId());

    if (count($userAuctions) == 1) {
      $auction->setId($userAuctions[0]->getId());
    } else {
      throw new Exception('A problem happenned while create an auction');
    }

    /*Click to accept the prevent created auction*/
    $url = '/?r=auctionManagement/validate&id=' . $auction->getId();
    visiteUrl($url);

    checkUrl($url);

    disconnect();

    connect($session, $user);
  }
}
