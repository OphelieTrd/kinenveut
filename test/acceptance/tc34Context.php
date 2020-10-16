<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc34Context implements Context
{
  /**
   * @Given l'utilisateur est sur la page de création d'enchère
   */
  public function lutilisateurEstSurLaPageDeCreationDenchere()
  {
    throw new PendingException();
  }

  /**
   * @When l'utilisateur entre un prix de départ valide
   */
  public function lutilisateurEntreUnPrixDeDepartValide()
  {
    throw new PendingException();
  }

  /**
   * @Then la création d'enchère est possible
   */
  public function laCreationDenchereEstPossible()
  {
    throw new PendingException();
  }
}