<?php include_once 'src/view/page-header.php' ?>

<?php
$auction = (isset($data['auction'])) ? $data['auction'] : new AuctionModel();
$auctionAccessState = (isset($data['auctionAccessState'])) ? $data['auctionAccessState'] : new AuctionAccessStateModel();
$seller = (isset($data['seller'])) ? $data['seller'] : new UserModel();

$bestBid = ($auction->getBestBid() != null) ? $auction->getBestBid() : new BidModel();
$minPrice = (($bestBid->getBidPrice() != null) && ($bestBid->getBidPrice() != null)) ? $bestBid->getBidPrice() : $auction->getBasePrice();
?>

<div class="container">

  <!--Titre-->
  <div class="row">
    <div class="col-md-9">
      <h2><?php echo protectStringToDisplay($auction->getName()); ?> - <?php echo $minPrice ?>€</h2>
    </div>
    <div class="col-md-3">
      <small><i>Mis en ligne le <?php echo $auction->getStartDate(); ?></i></small>
    </div>
  </div>

  <!--Image-->
  <div class="row">
    <div class="col-md-9">
      <?php if (strlen($auction->getPictureLink() > 1)) : ?>
        <img src="<?php $auction->getPictureLink(); ?>" width="100%" alt="Photo de l'image associée à l'enchère" />
      <?php endif; ?>
    </div>
    <div class="col-md-3"></div>
  </div>

  <!--Prix-->
  <div class="row">
    <div class="col-md-6">
      <?php if ($auction->getAuctionState() == 1) : ?>
        <?php if ($_SESSION['userId'] != $auction->getSellerId()) : ?>
          <?php if ($_SESSION['userId'] != $bestBid->getBidderId()) : ?>
            <?php if ($auction->getPrivacyId() == 0 || $auctionAccessState->getStateId() == 1) : ?>
              <form action="?r=bid/addBid&auctionId=<?= parameters()['auctionId']; ?>" method="post">
                <div class="input-group mb-2">
                  <input class="form-control" name="bidPrice" type="number" id="bidPrice" value="" min="<?php echo $minPrice + 1; ?>" placeholder="Saisir votre enchère maximum" />
                  <div class="input-group-prepend">
                    <div class="input-group-btn">
                      <input class="btn btn-light" name="makeabid" type="submit" value="Enchérir" />
                    </div>
                  </div>
                </div>
                <?php if (isset($_SESSION['errors']['noBidPrice'])) : ?>
                  <span class='error-custom'><?= $_SESSION['errors']['noBidPrice'] ?></span>;
                  <?php unset($_SESSION['errors']['noBidPrice']); ?>
                <?php endif; ?>
              </form>
            <?php else : ?>
              <?php if ($auctionAccessState->getStateId() !== null && $auctionAccessState->getStateId() == 0) : ?>
                <a class="btn btn-secondary" href="?r=bid/cancelAuctionAccessRequest&auctionId=<?= parameters()['auctionId']; ?>">Annuler ma demande</a>
              <?php else : ?>
                <a class="btn btn-primary" href="?r=bid/makeAuctionAccessRequest&auctionId=<?= parameters()['auctionId']; ?>">Demander à participer à l'enchère</a>
              <?php endif; ?>
            <?php endif; ?>
          <?php else : ?>
            Dernière offre :&nbsp<b><span style="color: green"><?php echo ' ' . $minPrice . ' €'; ?></span></b>
          <?php endif; ?>
        <?php else : ?>
          <?php if ($auction->getBasePrice() == $minPrice) : ?>
            <i>Aucune enchère n'a été effectuée pour le moment</i>
          <?php else : ?>
            Dernière offre :&nbsp<?php echo $minPrice . ' €'; ?>
          <?php endif; ?>
        <?php endif; ?>
      <?php else : ?>
        <?php if ($bestBid->getBidPrice() == null) : ?>
          Prix de base =&nbsp<?php echo $minPrice . ' €'; ?>
        <?php else : ?>
          Dernière offre :&nbsp<?php echo $minPrice . ' €'; ?>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <!--Line-->
  <div class="hr"></div>

  <!--Description-->
  <div class="row">
    <div class="col-md-12">
      <h3>Description</h3>
      <p>
        <?php echo protectStringToDisplay($auction->getDescription()); ?>
      </p>
    </div>
  </div>

  <!--Line-->
  <div class="hr"></div>

  <!--User-->
  <div class="row">
    <div class="col-md-9">

      <a href="?r=account/index&userId=<?= $seller->getId(); ?>">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
          <path fill-rule="evenodd" d="M2 15v-1c0-1 1-4 6-4s6 3 6 4v1H2zm6-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
        </svg>
        <?php echo $seller->getFirstName() . ' ' . strtoupper(substr($seller->getLastName(), 0, 1)); ?>
      </a>
    </div>

    <div class="col-md-3"></div>

  </div>

</div>
