<?php
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      //LIBRARIES
      include_once("libraries/dbconnect.php");

      function unverifiedCalls(){

        global $connection;
        $sql = "SELECT id, call_url FROM bat_calls WHERE verified IS false AND analyzed is true";
        $results = $connection->query($sql);
        return $results;

      }

      function unclassifiedCalls(){

        global $connection;
        $sql = "SELECT id FROM bat_classifications WHERE (common_pipistrelle + nathusius_pipistrelle + soprano_pipistrelle + myotis + brown_long_eared + lesser_Horseshoe + leislers_bat + unknown) < 10 AND EXISTS (SELECT 1 FROM bat_calls WHERE bat_calls.id = bat_classifications.id AND bat_calls.analyzed IS true AND bat_calls.verified = true)";
        $results = $connection->query($sql);
        return $results;

      }

      function showUnverified($results){

        $calls = [];
        while($row = $results->fetch_assoc()){
            array_push($calls, [$row['id'], $row['call_url']]);
        }
        return $calls[rand(0, sizeof($calls) - 1)];

      }

      function showUnclassified($results){

        global $connection;

        $ids = [];
        while($row = $results->fetch_assoc()){
            array_push($ids, $row['id']);
        }
        $call_id = $ids[rand(0, sizeof($ids) - 1)];
        $sql = "SELECT id, call_url FROM bat_calls WHERE id = {$call_id}";
        $results = $connection->query($sql);
        $row = $results->fetch_assoc();
        return [$call_id, $row['call_url']];

      }

      $tot_unverified = unverifiedCalls();
      $tot_unclassified = unclassifiedCalls();

      $toVerify = true;

      if($tot_unverified->num_rows > 0){

        //First have to see if there are unclassifieds

        if($tot_unclassified->num_rows > 0){

          //Have to decide if we want to show an unverified or an unclassified

          if(rand(0,1) == 0){

            list($call_id, $call_url) = showUnverified($tot_unverified);

          }else{

            $toVerify = false;
            list($call_id, $call_url) = showUnclassified($tot_unclassified);

          }

        }else{


            list($call_id, $call_url) = showUnverified($tot_unverified);

        }

      }else if($tot_unclassified->num_rows > 0){

        $toVerify = false;
        list($call_id, $call_url) = showUnclassified($tot_unclassified);

      }

    ?>
    <title>Identify</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="libraries/Spectrogram-Player/spectrogramplayer.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="libraries/Spectrogram-Player/spectrogramplayer.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
      $(document).ready(function(){
        sp_init();
      });
    </script>
  </head>
  <body>
    <?php
      include("includes/navigation.php");
    ?>
    <div class="content container-fluid">
        <?php if(isset($call_id)) : ?>
          <div class="classification-box row">
            <div class="spectrogram-player col-md-7" image-dimensions>
                <img src="<?php echo($call_url)?>spectrogram.png">
                <audio controls>
                  <source src="<?php echo($call_url)?>time_expansion.wav" type="audio/wav">
                </audio>
            </div>
            <div class="side-bar col-md-4 col-md-offset-1">
              <form method="POST" action="actions/classify.php">
                <?php if($toVerify) : ?>
                  <h3>Is this a bat call?</h3>
                  <button class="btn" name="confirm-call" type="submit" value="1">Yes</button>
                  <button class="btn" name="confirm-call" type="submit" value="0">No</button>
                  <input type="hidden" name="call-id" value="<?php echo($call_id) ?>"
                <?php else: ?>
                  <h3>What bat species is this?</h3>
                  <h4><i>Pippistrellus</i></h4>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="0">Common pipistrelle</button>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="1">Nathusius' pipistrelle</button>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="2">Soprano Pipistrelle</button>
                  <h4><i>Myotis<i></h4>
                  <p> Myotis bats cannot be identified reliably by their echolocation calls </p>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="3">Myotis bat</button>
                  <h4>Ungrouped</h4>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="4">Brown Long Eared</button>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="5">Lesser Horseshoe</button>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="6">Leisler’s bat</button>
                  <h4>Unable to identify?</h4>
                  <button class="btn bat-specie" name="bat-specie" type="submit" value="7">Do not know</button>
                <?php endif ?>
                <input type="hidden" name="call-id" value="<?php echo($call_id) ?>">
              </form>
            </div>
          </div>
        <?php else: ?>
          <div class="row info-text">
            <a>There are not bat calls to be classified at the momenent </a>
          </div>
        <?php endif; ?>
    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>
