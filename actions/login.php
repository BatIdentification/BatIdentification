<?php

  include_once("../libraries/dbconnect.php");
  include_once("../libraries/BatIDUser.php");

  //We get the users details
  $stmt = $connection->prepare("SELECT id, username, password, access_token FROM users WHERE email = ?");
  $stmt->bind_param("s", $_POST['email']);
  $stmt->execute();
  $stmt->bind_result($id, $username, $retrivedPassword, $access_token);
  $stmt->fetch();

  //Check the password
  if(password_verify($_POST['password'], $retrivedPassword)){

    $user = new BatIDUser($username, $_POST['email'], $id, $access_token);
    $user->beginSession();

    header("Location: " . $_POST['redirect']);

  }else{

    header("Location: ../login.php?denied");

  }

?>
