<?php
$dbOk = false;

// Create connection 
@$db = new mysqli('localhost', 'root', 'Marvin0101@', 'ecotrack'); 

if ($db->connect_error) {
    echo 'Could not connect to the database. Error: ' . $db->connect_errno;
    die();
} else {
    $dbOk = true;
}
?>