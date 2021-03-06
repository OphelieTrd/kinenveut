<?php include_once 'src/view/page-header.php' ?>

<?php

    date_default_timezone_set('Europe/Paris');

    $dateFormat = 'm d, Y H:i:s';
    $auction = (isset($data['auction'])) ? $data['auction'] : new AuctionModel();
    $auctionAccessState = (isset($data['auctionAccessState'])) ? $data['auctionAccessState'] : new AuctionAccessStateModel();
    $seller = (isset($data['seller'])) ? $data['seller'] : new UserModel();

    $bestBid = ($auction->getBestBid() != null) ? $auction->getBestBid() : new BidModel();
    $minPrice = (($bestBid->getBidPrice() != null) && ($bestBid->getBidPrice() != null)) ? $bestBid->getBidPrice() : $auction->getBasePrice();

    $isFinished = ($auction->getAuctionState() == 4);
?>

<div class="container">

    <div style="text-align: right;">
        <?php include 'src/view/common/privacyBadge.php';?>
        &nbsp;
    </div>

  <!--Titre-->
  <div class="row">
    <div class="col-md-9">
      <h2><?php echo protectStringToDisplay($auction->getName()); ?>
          <?php if ($auction->getPrivacyId() == 0
                    || ($auction->getPrivacyId() == 1 && ($auctionAccessState->getStateId() == 1))
                    || ($auction->getPrivacyId() == 2 && ($auctionAccessState->getStateId() == 1))
                    || $_SESSION['userId'] == $auction->getSellerId()) : ?>
          - <?php echo $minPrice ?>€
        <?php endif;?>
      </h2>
        <div id="timer">
            <?php if ($isFinished):?>
                L'enchère est terminée depuis le <?php echo dateTimeFormat($auction->getEndDate());?>
            <?php else:?>
                Expire dans : <?php echo((new DateTime('Now'))->diff($auction->getEndDate()))->format('%a jours %h:%i:%s');?>
            <?php endif;?></div>
        <br/>
    </div>


    <div class="col-md-3">
        <small>
            <i>
                <?php if ($auction->getAuctionState() != 0):?>
                Mis en ligne le
                <?php else:?>
                Créé le
                <?php endif;?>
                <?php echo dateFormat($auction->getStartDate()); ?>
            </i>
        </small>
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
            <div id="formulairePourEncherir">
            <?php if ($isFinished == false):?>
                  <form action="?r=bid/addBid&auctionId=<?= parameters()['auctionId']; ?>" method="post">
                      <div class="input-group mb-2">
                          <input class="form-control" name="bidPrice" type="number" id="bidPrice" value="" min="<?php echo ($auction->getBasePrice() == $minPrice && $bestBid->getBidPrice() != $minPrice) ? $minPrice : $minPrice + 1; ?>" placeholder="<?=$minPrice?>" step="1" />
                          <div class="input-group-prepend">
                              <div class="input-group-btn">
                                  <input class="btn btn-warning" id="makeabid" name="makeabid" type="submit" value="Enchérir" />
                              </div>
                          </div>
                      </div>
                      <?php if (isset($_SESSION['errors']['noBidPrice'])) : ?>
                          <span class='error-custom'><?= $_SESSION['errors']['noBidPrice'] ?></span>
                          <?php unset($_SESSION['errors']['noBidPrice']); ?>
                      <?php endif; ?>
                  </form>
            <?php endif;?>
            </div>
            <?php else : ?>
              <?php if ($auctionAccessState->getStateId() !== null && $auctionAccessState->getStateId() == 0) : ?>
                <a id="btnAuctionCancelRequest" class="btn btn-secondary" href="?r=bid/cancelAuctionAccessRequest&auctionId=<?= parameters()['auctionId']; ?>">Annuler ma demande</a>
              <?php elseif ($auctionAccessState->getStateId() == null || $auctionAccessState->getStateId() == 2) : ?>
                <a id="btnAuctionRequest" class="btn btn-primary" href="?r=bid/makeAuctionAccessRequest&auctionId=<?= parameters()['auctionId']; ?>">Demander à participer à l'enchère</a>
              <?php elseif ($auctionAccessState->getStateId() == 5):?>
                  <i id="forbidedAuctionAccess">Vous n'êtes pas autorisé à participer à cette enchère</i>
              <?php else:?>
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

  <?php if ($auction->getAuctionState() == 1):?>
    <!--Partager-->
    <div class="row">
        <?php if ($auction->getPrivacyId() == 0 || ($auction->getPrivacyId() == 1 && ($auctionAccessState->getStateId() == 1)) || $_SESSION['userId'] == $auction->getSellerId()) : ?>
        <div class="col-md-12">
            <h3>Partager</h3>
            <div class="col-md-6">
                <div class="input-group mb-2">
                    <input id="to-copy" class="form-control" type="text" value="" readonly/>
                    <div class="input-group-prepend">
                        <div class="input-group-btn">
                            <input id="copy" class="btn btn-secondary" value="Copier le lien"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Line-->
        <div class="hr"></div>
        <?php endif;?>
    </div>
    <div class="row">
        <!--Gestion-->
        <?php if ($_SESSION['userId'] == $auction->getSellerId()) : ?>
        <div class="col-md-12">
            <br/>
            <?php if ($bestBid != null && $bestBid->getBidPrice() > 0 && $minPrice >= $auction->getReservePrice()):?>
                <a href=<?php echo '?r=auction/abort&auctionId=' . $auction->getId() ?>>
                    <button class="btn btn-secondary">Clôturer</button>
                </a>
            <?php endif;?>
            <a href=<?php echo '?r=auction/cancel&auctionId=' . $auction->getId() ?>>
                <button class="btn btn-danger">Supprimer</button>
            </a>
        </div>
        <!--Line-->
        <div class="hr"></div>
    <?php endif;?>
    </div>
    <?php endif;?>

    <!--Gestion Admin-->
    <?php if ($auction->getAuctionState() == 0 && $_SESSION['isAdmin'] == true):?>
        <div class="row">
            <div class="col-md-12">
                <a href="?r=auctionManagement/validate&id=<?php echo $auction->getId(); ?>" class="btn btn-success">
                    Accepter
                </a>
                <a href="?r=auctionManagement/delete&id=<?php echo $auction->getId(); ?>" class="btn btn-danger">
                    Refuser
                </a>
            </div>
        </div>

        <!--Line-->
        <div class="hr"></div>
    <?php endif;?>

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

<script type="text/javascript">
    /*Remplissage du champs URL à copier*/
    document.getElementById("to-copy").value = window.location.href;

    var url = window.location.href,
        toCopy  = document.getElementById( 'to-copy' ),
        btnCopy = document.getElementById( 'copy' );

    /*Fonction copiant l'URL*/
    btnCopy.addEventListener( 'click', function(){
        toCopy.select();

        if (document.execCommand( 'copy') ) {
            console.info( 'lien copié');
        } else {
            console.info( 'lien non copié' )
        }
        return false;
    } );

    /*Gestion du timer*/
    // Set the date we're counting down to
    var countDownDate = new Date("<?=($auction->getEndDate())->format('Y-m-d H:i:s');?>").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var temps_restant = "";

        if(days > 0){
            temps_restant += days + " jours ";
        }
        if(temps_restant != "" || hours > 0){
            if(hours < 10) temps_restant += 0;
            temps_restant += hours + ":";
        }
        if(temps_restant != "" || minutes > 0){
            if(minutes < 10) temps_restant += 0;
            temps_restant += minutes + ":";
        }
        if(temps_restant != "" || seconds > 0){
            if(seconds < 10) temps_restant += 0;
            temps_restant += seconds + "";
        }

        // Display the result in the element with id="demo"
        document.getElementById("timer").innerHTML = "Expire dans : " + temps_restant;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "L'enchère est terminée depuis le <?php echo dateTimeFormatWithSeconds($auction->getEndDate());?>";
            document.getElementById("formulairePourEncherir").innerHTML = " ";
            document.getElementById("formulairePourEncherir").style.visibility="hidden";
        }
    }, 1000);

    function replaceTimer(){
        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var temps_restant = "";

        if(days > 0){
            temps_restant += days + " jours ";
        }
        if(temps_restant != "" || hours > 0){
            temps_restant += hours + ":";
        }
        if(temps_restant != "" || minutes > 0){
            temps_restant += minutes + ":";
        }
        if(temps_restant != "" || seconds > 0){
            temps_restant += seconds + "";
        }

        // Display the result in the element with id="demo"
        document.getElementById("timer").innerHTML = "Expire dans : " + temps_restant;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "L'enchère est terminée depuis le <?php echo dateTimeFormatWithSeconds($auction->getEndDate());?>";
            document.getElementById("formulairePourEncherir").innerHTML = " ";
            document.getElementById("formulairePourEncherir").style.visibility="hidden";
            //Todo : ici, on peut discrètement passer l'enchère à terminé ^^ si l'état est en cours
        }
    }
</script>