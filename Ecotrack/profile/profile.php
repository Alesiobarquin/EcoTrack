<?php
    session_start();
    if(isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        // Use $email in your queries/logic
    }
    require_once 'db_connect-profile.php';

    $username = '';
    $level = 0;
    $points = 0;
    $xpPercentage = 0;
    $earnedBadges = [];
    $badgeIcons = []; // Store earned badge icons

    if ($dbOk) {
        // Prepare the query to fetch the username and other profile info
        $query = "SELECT username, lvl, points, badges FROM users WHERE email = ?";
        $statement = $db->prepare($query);
        
        if ($statement) {
            // Bind the email parameter and execute the query
            $statement->bind_param("s", $email);
            $statement->execute();
            $result = $statement->get_result();
    
            if ($result->num_rows === 1) {
                // Fetch the user data
                $user = $result->fetch_assoc();
                $username = $user['username'];
                $points = $user['points'];
                $level = floor($points / 100);
                $xpPercentage = ($points % 100);

                $updateQuery = "UPDATE users SET lvl = ? WHERE email = ?";
                $updateStatement = $db->prepare($updateQuery);
                if ($updateStatement) {
                    $updateStatement->bind_param("is", $level, $email);
                    $updateStatement->execute();
                    $updateStatement->close();
                }

                $earnedBadges = json_decode($user['badges'], true) ?? [];

            }
            $statement->close();
        }
        // Fetch badge details for earned badges
        if (!empty($earnedBadges)) {
            $placeholders = implode(',', array_fill(0, count($earnedBadges), '?'));
            $badgeQuery = "SELECT image_url FROM badges WHERE id IN ($placeholders)";
            $badgeStatement = $db->prepare($badgeQuery);

            if ($badgeStatement) {
                $badgeStatement->bind_param(str_repeat('i', count($earnedBadges)), ...$earnedBadges);
                $badgeStatement->execute();
                $badgeResult = $badgeStatement->get_result();

                while ($badge = $badgeResult->fetch_assoc()) {
                    $badgeIcons[] = $badge['image_url'];
                }

                $badgeStatement->close();
            }
        }
        $db->close();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ecotrack Profile</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../resources/headerstyles.css">
    <link rel="stylesheet" href="../resources/profile.css"">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/dropdown.js" defer></script>
    <script src="./profile.js"></script>

    <section class="header">
        <h2><a href="./profile.php">
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
        <li class="menuButton"><a href="./profile.php">Profile</a></li>
        <li class="menuButton"><a href="../game_and_interactive/badges.php">Badges</a></li>
        <li class="menuButton"><a href="../game_and_interactive/leaderboard.php">Leaderboard</a></li>
        <li class="menuButton"><a href="../logger_tips/activity_logger.html">Activity Logger</a></li>
        <li class="menuButton"><a href="../logger_tips/sustainability_tips.php">Sustainability Tips</a></li>
        <p class="logOut">Log out</p>
    </div>
</head>

<body>
    <section class="userInfo">
        <ul>
            <li>Name: <?php echo htmlspecialchars($username); ?></li>
            <li id="levelAmount">
                <div id="levelInfo">
                    Level: <?php echo htmlspecialchars($level); ?>
                </div>
                <div id="xpAmount">
                    <?php echo htmlspecialchars($xpPercentage); ?>/100
                </div>
            </li>
            <div id="levelContainer">
                <div id="levelBar" style="width: <?php echo htmlspecialchars($xpPercentage); ?>%;"></div>
            </div>
            <li>Badges:</li>
            <section id="displayBadges">
                <?php if (!empty($badgeIcons)): ?>
                    <?php foreach ($badgeIcons as $icon): ?>
                        <li>
                            <img src="<?= htmlspecialchars($icon) ?>" height="40px" width="40px" alt="Badge">
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No badges earned yet.</p>
                <?php endif; ?>
            </section>
        </ul>
    </section>
    <section id="redirectForm" >
        Fill out the form here
    </section>
</body>