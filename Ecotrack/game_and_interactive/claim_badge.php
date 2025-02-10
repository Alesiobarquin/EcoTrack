<?php
session_start();
require_once 'db_connect-profile.php'; // Adjust path to your DB connection script

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];
$badgeId = intval($_POST['badge_id']);

if ($dbOk) {
    // Fetch user details
    $query = "SELECT points, badges FROM users WHERE email = ?";
    $statement = $db->prepare($query);

    if ($statement) {
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $points = $user['points'];
            $earnedBadges = json_decode($user['badges'], true) ?? [];

            // Fetch badge details
            $badgeQuery = "SELECT points_required FROM badges WHERE id = ?";
            $badgeStmt = $db->prepare($badgeQuery);
            $badgeStmt->bind_param("i", $badgeId);
            $badgeStmt->execute();
            $badgeResult = $badgeStmt->get_result();

            if ($badgeResult->num_rows === 1) {
                $badge = $badgeResult->fetch_assoc();
                $pointsRequired = $badge['points_required'];

                // Check if user has enough points and hasn't claimed the badge
                if ($points >= $pointsRequired && !in_array($badgeId, $earnedBadges)) {
                    $earnedBadges[] = $badgeId;

                    // Update the database
                    $updateQuery = "UPDATE users SET badges = ? WHERE email = ?";
                    $updateStmt = $db->prepare($updateQuery);
                    $badgesJson = json_encode($earnedBadges);
                    $updateStmt->bind_param("ss", $badgesJson, $email);
                    $updateStmt->execute();

                    header('Location: badges.php?status=claimed');
                    exit();
                }
            }
        }
    }
}

header('Location: badges.php?status=error');
exit();
?>
