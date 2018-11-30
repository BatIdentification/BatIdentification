<?php

  //Include the neccessary libraries
  include_once("../libraries/dbconnect.php");
  include_once("../libraries/APIHandler.php");
  include_once("../libraries/BatIDUser.php");

  /*
  * Ensures that the username or email that has been submitted are not already in the datebase.
  */
  function checkInfo($username, $email){

    global $connection;

    $check = $connection->prepare("SELECT username, email from users WHERE (username = ? OR email = ?)");
    $check->bind_param("ss", $_POST['username'], $_POST['email']);
    $check->execute();
    $check->bind_result($username, $email);
    $check->fetch();
    $check->close();

    if($username == $_POST['username']){
      return array(false, "Sorry that username is taken");
    }

    if($email == $_POST['email']){
      return array(false, "Sorry that email is taken");
    }

    return array(true, "success");

  }

  //We need to ensure that all the values have been submited.
  if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){

    $check = checkInfo($_POST['username'], $_POST['email']);

    if($check[0]){

      $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt->bind_param("sss", $_POST['username'], $_POST['email'], $hashed);
      $stmt->execute();
      $stmt->close();

      $user = new BatIDUser($_POST['username'], $_POST['email'], $connection->insert_id);

      try{

        //We get the access token in signup so that it dosn't repeatedly generate it as it would if it we used the API to login everytime
        $handler = new APIHandler();
        $access_token = $handler->getAccessToken($_POST['email'], $_POST['password']);

        $user->setAccessToken($access_token);

        $stmt = $connection->prepare("UPDATE users SET access_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $access_token, $_POST['email']);
        $stmt->execute();
        $stmt->close();

        $user->beginSession();

        header("Location: ../");

      }catch (Exception $e){

        //An exception was raised from when we got the access_token

        $user->delete($connection);

        $error = json_decode($e->getMessage());

        header("Location: ../login.php?signupError={$error->error}");

      }

    }else{

      //Either the username or email is already taken
      header("Location: ../login.php?signupError=" . $check[1]);

    }

  }else{

    //Not all the neccessary data was passed
    header("Location: ../login.php?invalidSignup");

  }


?>
