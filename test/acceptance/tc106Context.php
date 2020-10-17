<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc106Context implements Context
{
  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct()
  {
  }

  /**
   * @Given L'utilisateur est normal
   */
  public function lutilisateurEstNormal()
  {
    $user = new UserModel();
    $user
      ->setFirstName('Francis')
      ->setLastName('Dupont')
      ->setBirthDate(DateTime::createFromFormat('d/m/Y', '22/12/1999'))
      ->setEmail('francis.dupont@gmail.com')
      ->setPassword('password');

    Universe::getUniverse()->setUser($user);
  }

  /**
   * @Given L'utilisateur a posté des enchères.
   */
  public function lutilisateurAPosteDesEncheres()
  {
    throw new PendingException();
  }

  /**
   * @When L'utilisateur consulte ses enchères.
   */
  public function lutilisateurConsulteSesEncheres()
  {
    throw new PendingException();
  }

  /**
   * @Then L'utilisateur voit toutes les enchères qu'il a posté.
   */
  public function lutilisateurVoitToutesLesEncheresQuilAPoste()
  {
    throw new PendingException();
  }
}