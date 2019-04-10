<?php

  include_once("../libraries/dbconnect.php");

  session_name("batidentification");
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();

  $url = $api_url . "/api/upload";

  if(file_exists($_FILES['bat_call']['tmp_name']) && isset($_POST['date_recorded']) && isset($_POST['location']) ){

    //Prepare the call for uploading

    $callToUpload = new CurlFile($_FILES['bat_call']['tmp_name'], $_FILES['bat_call']['type'], $_FILES['bat_call']['name']);

    //Split the lag, lng getCoordinates

    list($lat, $lng) = explode(",", $_POST['location']);

    //Define the post variables which we are going to send

    $data = array('bat_call' => $callToUpload, 'date_recorded' => $_POST['date_recorded'], 'lat' => trim($lat), 'lng' => trim($lng));

    //Send up the curl request

    try{

      $ch = curl_init();

      if($ch === false){

        throw new Exception('{"error": "connection_failed", "error_description": "Sorry, something went wrong file uploading the call"}');

      }

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

      //Define our headers

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-type: multipart/form-data",
          "Authorization: Bearer {$_SESSION['access_token']}",
        )
      );

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      //Execute and print output

      $server_output = curl_exec($ch);

      curl_close($ch);

    }catch (Exception $e){

      $server_output = $e->getMessage();

    }

  }else{

    $server_output = '{"error": "insufficent_data", "error_description": "Sorry some data was missing"}';

  }

  $response = json_decode($server_output);

  if(isset($response->error)){

    $data = json_decode($server_output);

    header("Location: ../profile.php?warning=" . rawurlencode($data->error_description));

  }elseif(isset($response->success)){

    header("Location: ../profile.php?success");

  }else{

    header("Location: ../profile.php?warning=" . rawurlencode("Sorry something went wrong"));

  }


?>
