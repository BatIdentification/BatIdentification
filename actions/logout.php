<?php

  session_name("batidentification");
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();
  session_destroy();
  header("Location: ../index.php");

?>
