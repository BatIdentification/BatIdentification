<!DOCTYPE html>
<?php
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();
  require("libraries/dbconnect.php");
?>
<html lang="en">
  <head>
    <title>BatAnalyzer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
    <?php
      include("includes/navigation.php");
    ?>
    <div class="content container-fluid batanalyzer-download central-box">
      <h2>BatAnalyzer</h2>
      <p>BatAnalyzer is a citizen-science application which allows for you, <i>yes you the budding citizen-science</i>, to begin to help conserve our bat species. It helps us efficently analyze our bat calls permitting others to correctly verify them and identify the species present in them.</p>
      <h4>What does it do</h4>
      <p>This application will download bat calls from our servers and create spectrograms(graphs of frequencies verus time) as well as time expansion audio of them. Both of these things are neccessary to identify the species in bat calls.</p>
      <h4>Requirments</h4>
      <p>Currently the program is only available on macOS but a windows version will be out soon!</p>
      <a href="downloads/BatIdentification.zip" class="btn btn-primary">Download</a>
    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>
