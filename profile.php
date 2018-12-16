<?php
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();
  require("libraries/dbconnect.php");
  if(!isset($_SESSION['username'])){
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
      function notify(text, positive = false){
        $("#warning-label").html(text);
        if(positive == true){
          $("#warning-label").css('color', "green");
        }
        setInterval(function(){
          $("#warning-label").html("");
          $("#warning-label").css('color', "red");
        }, 5000)
      }

      $(document).ready(function(){
        var getParameter = decodeURIComponent(window.location.search.substr(1));
        if(getParameter.includes("warning")){
          notify(getParameter.split("=")[1]);
        }else if(getParameter.includes("success")){
          notify("Successfully uploaded call", true)
        }else if(getParameter.includes("upload")){
          $(".overlay").toggle();
          $("body").addClass("stop-scrolling");
        }
      });
    </script>
  </head>
  <body>
    <?php
      include("includes/navigation.php");
      $calls = array();
      $verifiedCalls = 0;
      $analyzedCalls = 0;
      $stmt = $connection->prepare("SELECT DATE(date_recorded), call_url, classification, address, verified, analyzed FROM bat_calls WHERE user_id = ?");
      $stmt->bind_param("i", $_SESSION['id']);
      $stmt->execute();
      $stmt->bind_result($date_recorded, $call_url, $specie, $address, $verified, $analyzed);
      while($stmt->fetch()){
          $verifiedCalls += $verified;
          $analyzedCalls += $analyzed;
          array_push($calls, [$date_recorded, $call_url, ucwords(str_replace("_", " ", $specie)), $address, $verified, $analyzed]);
      }
      $stmt->close();
    ?>
    <h5 id="warning-label"></h3>
    <div class="content container-fluid central-box">
      <div class="row">
        <div class="col-md-9 container-fluid user-content">
          <div class="row profile-header">
            <div class="col-md-12">
              <h3>Welcome <?php echo($_SESSION['username']) ?>!</h3>
              <ul class="nav navbar-nav">
                <li><a id="upload">Upload a new call</a></li>
                <li><a>Settings</a></li>
                <li><a href="actions/logout.php">Logout</a></li>
              </ul>
              <hr>
            </div>
          </div>
          <div class="row uploaded-calls">
            <div class="col-md-12">
                <?php

                  foreach($calls as $call){
                      $specie = $call[2] == '' ? 'Unknown' : $call[2];
                      $verified = ($call[4] == 1 ? "" : "text-muted");
                      $analyzed = ($call[5] == 1 ? "" : "text-muted");
                      echo("
                          <div class='uploaded-call container-fluid'>
                            <div class='row'>
                              <div class='col-md-10'>
                                <h4>{$specie}</h4>
                                <p>{$call[0]} {$call[3]}</p>
                                <b class='{$verified}'>Verified</b>
                                <b class='{$analyzed}'>Analyzed</b>
                              </div>
                              <div class='col-md-2 call-actions'>");
                      if($call[4]){
                        echo("<a href='{$call[1]}spectrogram.png'><i class='fas fa-image fa-3x'></i></a>");
                      }
                      if($call[5]){
                        echo("<a href='{$call[1]}time_expansion.wav'><i class='fas fa-file-audio fa-3x'></i></a>");
                      }
                      echo('
                              </div>
                            </div>
                          </div>
                      ');
                  }

                ?>
            </div>
          </div>
        </div>
        <div class="col-md-3 container-fluid user-stats">
          <div class="row profile-header">
              <div class="col-md-12">
                <h3>Statistics</h3>
              </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <span class="stat"><b><?php echo(count($calls));?></b> calls uploaded</span>
                <br>
                <span class="stat-sub"><b><?php echo($analyzedCalls);?></b> have been analyzed</span>
                <br>
                <span class="stat-sub"><b><?php echo($verifiedCalls);?></b> have been verified</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="overlay">
        <div class="upload-container" >
          <form action="actions/upload_audio.php" method="POST" enctype="multipart/form-data">
              <h2>Upload a new bat call</h2>
              <hr>
              <div class="form-group">
                  <label> Please upload your bat call:</label>
                  <label class="btn btn-primary">
                      <input type="file" id="bat-call" name="bat_call">
                      Browse
                  </label>
                  <span class="label label-info" id="upload-file-info"></span>
              </div>
              <div class="form-group">
                  <label for="location">Where did you record this call:</label>
                  <input type="text" class="form-control" name="location" id="location" placeholder="33.4553, 55.3238">
              </div>
              <div class="form-group">
                  <label for="date">When did you record this call:</label>
                  <input type="text" class="form-control" name="date_recorded" id="date" placeholder="2017-05-18 14:33:19">
              </div>
              <div class="options">
                <button class="btn btn-primary">Upload</button>
                <button class="btn btn-danger" type="button" id="cancel-upload">Cancel</button>
              </div>
          </form>
        </div>
    </div>
    <?php
      include("includes/footer.php");
    ?>
    <script>
      $(document).ready(function(){
        $("#upload").click(function(){
          $(".overlay").toggle();
          $("body").addClass("stop-scrolling");
        });
        $("#bat-call").change(function(){
          $('#upload-file-info').html(this.files[0].name);
        });
        $("#cancel-upload").click(function(){
          $(".overlay").toggle();
          $("body").removeClass("stop-scrolling");
        });
      });
    </script>
  </body>
</html>
