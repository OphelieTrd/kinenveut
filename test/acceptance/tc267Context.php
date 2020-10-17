<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc267Context implements Context
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
   * @Given L'utilisateur est sur la page de mise à jour de profile
   */
  public function lutilisateurEstSurLaPageDeMiseAJourDeProfile()
  {
    throw new PendingException();
  }

  /**
   * @Given L'utilisateur a entré la valeur :arg1 dans le champ :arg2
   */
  public function lutilisateurAEntreLaValeurDansLeChamp($arg1, $arg2)
  {
    throw new PendingException();
  }

  /**
   * @Then Le champ :arg1 du profile contient la valeur :arg2
   */
  public function leChampDuProfileContientLaValeur($arg1, $arg2)
  {
    throw new PendingException();
  }
}