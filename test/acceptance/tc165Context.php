<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc165Context implements Context
{
  /**
   * @Given L'utilisateur à effectué des achats et\/ou des ventes.
   */
  public function lutilisateurAEffectueDesAchatsEtOuDesVentes()
  {
    throw new PendingException();
  }

  /**
   * @When L'utilisateur consulte son historique.
   */
  public function lutilisateurConsulteSonHistorique()
  {
    throw new PendingException();
  }

  /**
   * @Then L'utilisateur voit son historique.
   */
  public function lutilisateurVoitSonHistorique()
  {
    throw new PendingException();
  }
}
