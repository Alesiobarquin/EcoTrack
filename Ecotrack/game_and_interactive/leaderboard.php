<?php
session_start();
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    // Use $email in your queries/logic
}
require_once 'db_connect-profile.php';

$username = '';
$level = 0;
$points = 0;
$sessionUser = null; // Store session user
$topUsers = [];

// Fetch session user's information
if ($dbOk) {
    $query = "SELECT username, lvl, points FROM users WHERE email = ?";
    $statement = $db->prepare($query);

    if ($statement) {
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 1) {
            $sessionUser = $result->fetch_assoc(); // Store session user info here
            $username = $sessionUser['username'];
            $level = $sessionUser['lvl'];
            $points = $sessionUser['points'];
        }
        $statement->close();
    }

    // Fetch the top 10 leaderboard users (including the session user if they are in the top 10)
    $query = "SELECT username, points FROM users ORDER BY points DESC LIMIT 10";
    $result = $db->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $topUsers[] = $row; // Store each user's data in the array
        }
        $result->close();
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ecotrack Leaderboard</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../resources/headerstyles.css">
    <link rel="stylesheet" href="../resources/leaderboard.css"">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/dropdown.js" defer></script>
    <!-- <script src="./profile.js"></script> -->

    <section class="header">
        <h2><a href="../profile/profile.php">
            <img src="../resources/EcotrackLogo.png" width="70px" height="70px">
        </a></h2>
        <h1>
            Ecotrack
        </h1>
        <h3>
            <img src="../resources/Dropdown.png" class="dropDownMenu" width="60px" height="60px">
        </h3>
    </section>
    <div class="menu">
        <li class="menuButton"><a href="../profile/profile.php">Profile</a></li>
        <li class="menuButton"><a href="./badges.php">Badges</a></li>
        <li class="menuButton"><a href="./leaderboard.php">Leaderboard</a></li>
        <li class="menuButton"><a href="../logger_tips/activity_logger.html">Activity Logger</a></li>
        <li class="menuButton"><a href="../logger_tips/sustainability_tips.php">Sustainability Tips</a></li>
        <p class="logOut">Log out</p>
    </div>
</head>
<body>
    <section id="leaderboardContainer">
        <h1>Leaderboard</h1>
        <section id="top10">
            <ol>
                <?php foreach ($topUsers as $index => $user): ?>
                    <li class="<?php echo $index < 3 ? 'crown crown-' . ($index + 1) : ''; ?>">
                        <?php if ($index < 3): ?>
                            <span class="crown-icon crown-<?php echo $index + 1; ?>"></span>
                        <?php else: ?>
                            <?php echo ($index + 1) . ". "; ?>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($user['username']) . " - " . htmlspecialchars($user['points']) . " points"; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>
    </section>
    <section id="userPosition">
        Your Stats:
        <li><?php echo htmlspecialchars($sessionUser['username']); ?> - <?php echo htmlspecialchars($sessionUser['points']); ?> points</li>
    </section>
</body>