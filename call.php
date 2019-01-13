<?php
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();
?>
<?php

  require_once("libraries/dbconnect.php");

  $stmt = $connection->prepare("SELECT call_url, analyzed FROM bat_calls WHERE id = ?");

  if (ctype_digit($_GET['id'])) {

    $id = (int)$_GET['id'];

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->bind_result($call_url, $analyzed);

    $stmt->fetch();

    $stmt->close();

    if($call_url == "" || $analyzed == 0){

      header("Location: index");
      die();

    }

  }else{

    header("Location: index");
    die();

  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Call</title>
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
        <div class="row">
          <div class="col-md-12">
            <div class="spectrogram-player call-player" image-dimensions>
                <img src="/<?php echo($call_url)?>spectrogram.png">
                <audio controls>
                  <source src="/<?php echo($call_url)?>time_expansion.wav" type="audio/wav">
                </audio>
            </div>
          </div>
    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>
