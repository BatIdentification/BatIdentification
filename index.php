<?php
  require('libraries/dbconnect.php');

  session_name("batidentification");
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();

  $sql = "SELECT (SELECT COUNT(*) FROM bat_calls) as tot_calls, (SELECT COUNT(*) FROM bat_calls WHERE verified = true) as tot_verified, (SELECT COUNT(*) FROM bat_calls WHERE analyzed = true) as tot_analyzed";

  $result = $connection->query($sql);
  $data = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>BatIdentification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontawesome.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
      include("includes/navigation.php");
    ?>
    <div class="content container-fluid">
      <div class="row info-text">
          <?php

            echo("<a>{$data['tot_calls']} calls, {$data['tot_analyzed']} analyzed, {$data['tot_verified']} verified and counting!</a>");

          ?>
      </div>
      <div class="row boxes">
          <div class="col-md-4">
              <div class="info-box">
                <h3>Contribute calls</h3>
                <p>Bat calls can be uploaded by any citizen scientist, like you, around the country. Together we will create a repository of calls so that the current state of our bat species can be monitored.</p>
              </div>
          </div>
          <div class="col-md-4">
              <div class="info-box">
                <h3>Identify species</h3>
                <p>Listen to bat calls and help us decide which species of bat it is. By crowd-sourcing this information we will be able to more efficiently monitor and conserve our bat populations.</p>
              </div>
          </div>
          <div class="col-md-4">
            <div class="info-box">
              <h3>Learn about bats</h3>
              <p>Get to know these highly interesting species of animals. Become familiar with the different kinds of bats, their traits, and what their role in biodiversity is.</p>
            </div>
          </div>
      </div>
    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>
