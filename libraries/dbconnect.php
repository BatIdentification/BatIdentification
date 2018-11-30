<?php
    function db_connect() {

        // Define connection as a static variable, to avoid connecting more than once
        static $connection;

        // Try and connect to the database, if a connection has not been established yet
        if(!isset($connection)) {
            // $path = $_SERVER["DOCUMENT_ROOT"] . '/../private/configBatIdentification.ini';
           	$path = "/shared/httpd/private/configBatIdentification.ini";
            $config = parse_ini_file($path);
            $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
        }
        // If connection was not successful, handle the error
        if($connection === false) {
        	echo("Not succesful");
          // Handle error - notify administrator, log to a file, show an error screen, etc.
          echo(mysqli_connect_error());
        }
        return [$connection, $config['servername'], $config['dbname'], $config['username'], $config['password']];
    }

    // Connect to the database
    list($connection, $dbhost, $dbname, $dbuser, $dbpass) = db_connect();

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    ?>
