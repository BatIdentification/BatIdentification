<?php

  class BatIDUser{

    function __construct($username, $email, $id, $access_token = NULL){

      $this->username = $username;
      $this->email = $email;
      $this->id = $id;
      $this->access_token = $access_token;

    }

    /*
    * Moves the users username, email, id and access_token into session values
    */
    function beginSession(){

      session_set_cookie_params(0, '/', '.batidentification.com');
      session_start();

      $_SESSION['username'] = $this->username;
      $_SESSION['email'] = $this->email;
      $_SESSION['id'] = $this->id;
      $_SESSION['access_token'] = $this->access_token;

    }

    /*
    * Removes the user from the MySQL database
    */
    function delete($connection){

      $stmt = $connection->prepare("DELETE FROM users WHERE email = ? AND username = ?");
      $stmt->bind_param("ss", $this->email, $this->username);
      $status = $stmt->execute();
      $stmt->close();

      return $status;

    }

    /*
    * Sets the access_token value
    */
    function setAccessToken($access_token){
      $this->access_token = $access_token;
    }

  }

?>
