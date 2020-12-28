<?php   // $db = new mysqli("localhost","6239010023","pass6239010023","6239010023");
    $db = new mysqli("localhost","6239010023","6239010023","6239010023");
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    ?>