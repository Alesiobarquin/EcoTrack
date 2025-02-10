<?php
$first_name = "Your";

$data = [
    "first_name" => $first_name,
];

header('Content-Type: application/json');
echo json_encode($data)
?>