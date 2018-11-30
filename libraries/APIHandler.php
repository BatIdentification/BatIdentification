<?php

  /**
  * Handles communicating with api.batidentification.com
  */
  class APIHandler{

    public function __construct() {
      $this->client_id = "92Z17HPJRYUTDUFJUMUXT8FMGDLUMEFV";
      $this->client_secret = "73PKBLqyPRsGVMaWivowZ5bfsbUYcN1iaUbXGQuzsDOnkRi6a6YQrA769yNNHlOchRju99IfWHxC8D1A";
    }

    public function getAccessToken($email, $password){

      //CURL variables
      $url = "https://api.batidentification.loc/token.php";
      $data = array('grant_type' => 'password', 'client_id' => $this->client_id, 'client_secret' => $this->client_secret, 'username' => $email, 'password' => $password);

      try{

        $ch = curl_init();

        if($ch === false){

          throw new Exception('{"error": "connection_failed", "error_description": "Sorry, something went wrong while authenticating"}');

        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        //receive server response

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Execute and print output

        $server_output = curl_exec($ch);

        curl_close($ch);

      }catch (Exception $e){

        $server_output = $e->getMessage();

      }

      $response = json_decode($server_output);

      if($response->access_token){
        return $response->access_token;
      }else{
        throw new Exception($server_output);
      }

    }

  }

?>
