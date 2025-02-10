<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbOk) {
    // Get and clean the input data
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = trim($_POST["password"]);  // Password will be hashed, so no htmlspecialchars

    $errors = '';

    // Validate input
    if ($username == '') {
        $errors .= '<li>Username may not be blank</li>';
    }
    if ($email == '') {
        $errors .= '<li>Email may not be blank</li>';
    }
    if ($password == '') {
        $errors .= '<li>Password may not be blank</li>';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= '<li>Invalid email format</li>';
    }

    if ($errors != '') {
        http_response_code(400);
        echo json_encode(['error' => $errors]);
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the insert statement
        $insQuery = "INSERT INTO users (username, email, password, lvl, points) VALUES (?, ?, ?, 0, 0)";
        $statement = $db->prepare($insQuery);
        
        if ($statement) {
            $statement->bind_param("sss", $username, $email, $hashedPassword);
            
            try {
                if ($statement->execute()) {
                    http_response_code(201);
                    echo json_encode(['message' => 'User registered successfully']);
                } else {
                    if ($db->errno == 1062) { // Duplicate entry error
                        http_response_code(409);
                        echo json_encode(['error' => 'Username or email already exists']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Registration failed']);
                    }
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }

            $statement->close();
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Could not prepare statement']);
        }
    }
    $db->close();
}
?>