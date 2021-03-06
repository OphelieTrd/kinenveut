<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class tc339Context implements Context
{
  /**
   * @Given l'utilisateur a recherché des enchères privées
   */
  public function lutilisateurARechercheDesEncheresPrivees()
  {
    throw new PendingException();
  }

  /**
   * @When l'utilisateur clique sur le bouton d'inscription à une enchère privée
   */
  public function lutilisateurCliqueSurLeBoutonDinscriptionAUneEncherePrivee()
  {
    throw new PendingException();
  }

  /**
   * @Then une demande d'inscription est envoyée à l'administrateur de l'enchère
   */
  public function uneDemandeDinscriptionEstEnvoyeeALadministrateurDeLenchere()
  {
    throw new PendingException();
  }
}
