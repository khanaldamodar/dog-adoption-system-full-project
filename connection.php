<?php 
  //process to login with database
 $connection = new mysqli('localhost','root','','dog-adoption-project');
        if ($connection->connect_errno != 0)
        {
            die('Database Connection Error: ' . $connection->connect_error);
        }

    ?>