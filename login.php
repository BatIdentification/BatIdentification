<?php
  session_name("batidentification");
  session_set_cookie_params(0, '/', '.batidentification.loc');
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/fontawesome.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/batidentification.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
      function toggleForm(){
        $(".login-box").toggle();
        $(".signup-box").toggle();
        modeText = $('#mode-button').text() == 'Login' ? 'Signup' : 'Login';
        $('#mode-button').html(modeText);
      }
      function warn(text){
        $("#warning-label").html(text);
        setInterval(function(){
          $("#warning-label").html("");
        }, 5000)
      }

      $(document).ready(function(){
          var messages = {'denied': 'Sorry, that username and / or password are incorrect', 'usernameTaken': 'Sorry, that username is already taken.', 'emailTaken': 'Sorry, that email is already taken.', 'invalidSignup': 'Sorry, some data was missing', 'serverError': "Sorry there was on a error on a servers, please try again later"};
          $("#mode-button").click(function(){
            toggleForm();
          });
          var getParameter = window.location.search.substr(1)
          if(getParameter.includes("redirect")){

          }else if(getParameter != ""){
            toggleForm();
            warn(messages[getParameter]);
          }

          if(getParameter == 'denied'){
            toggleForm();
          }

          $("#signup_form").submit(function(event){
            if($("#password_confirm").val() != $("#password").val()){
              event.preventDefault();
              warn("The passwords inserted do not match")
            }
          });

      });
    </script>
  </head>
  <body>
    <?php
      include("includes/navigation.php");
    ?>
    <div class="content container-fluid">
      <div class="row box-container">
        <h5 id="warning-label"></h3>
        <div class="form-box col-md-4 col-md-offset-4">
          <div class="login-box">
            <h2>Login</h2>
            <form action="actions/login.php" method="POST">
                <?php
                  $url =  'https://' . $_SERVER['SERVER_NAME'];
                  $redirect = isset($_SESSION['ref']) ? $_SESSION['ref'] : $url;
                  echo("<input type='hidden' name='redirect' id='redirect' value='{$redirect}'>");
                ?>
                <p>Email:</p>
                <input class="form-control" type="text" name="email">
                <p>Password:</p>
                <input class="form-control" type="password" name="password">
                <input class="btn btn-primary" id="login-button" type="submit" value="Login">
                <div id="google-signin"></div>
                <div hidden id="token-params">
                  <input name="grant_type" value="password">
                  <input name="client_id" value="8KCVSRHGKPRS5R9Y6OYP41MDIB1KM1O9">
                </div>
              </form>
          </div>
          <div class="signup-box">
            <h2>Signup</h2>
            <form action="actions/signup.php" method="POST" id="signup_form">
                <p>Username</p>
                <input class="form-control" type="text" name="username">
                <label for="email">Email:</label>
                <input class="form-control" type="text" id="email" name="email">
                <label for="password">Password:</label>
                <input class="form-control" type="password" name="password" id="password" autocomplete="new-password">
                <input class="form-control" type="password" name="password-reentered" id="password_confirm" autocomplete="new-password">
                <input class="btn btn-primary" type="submit" value="Signup">
            </form>
          </div>
          <div id='mode-button-container'>
            <a id='mode-button'>Signup</a>
          </div>
        </div>
      </div>
    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>
