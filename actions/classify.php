<?php

  include_once("../libraries/dbconnect.php");
  $bat_species = ['common_pipistrelle', 'nathusius_pipistrelle', 'soprano_pipistrelle', 'myotis', 'brown_long_eared', 'lesser_horseshoe', 'leislers_bat', 'unknown'];

  if(isset($_POST['confirm-call'])){
    $stmt = $connection->prepare("UPDATE bat_calls SET verified = ? WHERE id = ?");
    $call_id = intval($_POST['call-id']);
    $verified = intval($_POST['confirm-call']);
    $stmt->bind_param("ii", $verified, $call_id);
    $stmt->execute();
    $stmt->close();
  }elseif(isset($_POST['bat-specie'])){
    $call_id = intval($_POST['call-id']);
    if($_POST['bat-specie'] < sizeof($bat_species)){
      $confirmedSpecie = $bat_species[$_POST['bat-specie']];
      $stmt = $connection->prepare("UPDATE bat_classifications SET {$confirmedSpecie} = {$confirmedSpecie} + 1 WHERE id = ?");
      var_dump($connection->error);
      $stmt->bind_param("i", $call_id);
      $stmt->execute();
      $stmt->close();
    }else{
      http_response_code(400);
      echo("Please submit a valid bat specie identifier");
    }
  }

  header("Location: ../identify.php");

?>
