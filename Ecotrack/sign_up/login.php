
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Use $email in your queries/logic

}

require_once 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbOk) {
    // Get and clean the input data
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = trim($_POST["password"]);

    $errors = '';

    // Validate input
    if ($email == '') {
        $errors .= '<li>Email may not be blank</li>';
    }
    if ($password == '') {
        $errors .= '<li>Password may not be blank</li>';
    }

    if ($errors != '') {
        http_response_code(400);
        echo json_encode(['error' => $errors]);
    } else {
        // Prepare the select statement
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $db->prepare($query);
        
        if ($statement) {
            $statement->bind_param("s", $email);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['password'])) {
                    // Store email in session
                    $_SESSION['email'] = $email;

                    // Login successful
                    http_response_code(200);
                    echo json_encode([
                        'message' => 'Login successful',
                        'email' => $email
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode(['error' => 'Invalid email or password']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid email or password']);
            }

            $statement->close();
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Could not prepare statement']);
        }
    }
    $db->close();
}