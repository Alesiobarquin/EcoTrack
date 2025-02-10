<?php
    session_start();
    if(isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        // Use $email in your queries/logic
    }
    require_once 'db_connect-profile.php';

    $username = '';
    $points = 0;
    $earnedBadges = [];

    if ($dbOk) {
        // Prepare the query to fetch the username and other profile info
        $query = "SELECT username, points, badges FROM users WHERE email = ?";
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
                $earnedBadges = json_decode($user['badges'], true) ?? [];

            }
            $statement->close();
        }
        $badgeQuery = "SELECT * FROM badges";
        $badgeResult = $db->query($badgeQuery);
        $badges = $badgeResult->fetch_all(MYSQLI_ASSOC);
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ecotrack Badges</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../resources/headerstyles.css">
    <link rel="stylesheet" href="../resources/badges.css"">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/dropdown.js" defer></script>

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
    <section id="badgeContainer">
        <section id="myBadges">
            My Badges:
            <div class="badge-container">
                <?php foreach ($badges as $badge): ?>
                    <?php if (in_array($badge['id'], $earnedBadges)): ?>
                        <div class="badge earned">
                            <img src="<?= htmlspecialchars($badge['image_url']) ?>" alt="<?= htmlspecialchars($badge['name']) ?>">
                            <p><?= htmlspecialchars($badge['name']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <section id="claimSection">
            Challenges:
            <div class="badge-container">
                <?php foreach ($badges as $badge): ?>
                    <div class="badge">
                        <img src="<?= htmlspecialchars($badge['image_url']) ?>" alt="<?= htmlspecialchars($badge['name']) ?>">
                        <h3><?= htmlspecialchars($badge['name']) ?></h3>
                        <p><?= htmlspecialchars($badge['description']) ?></p>
                        <p>Points required: <?= htmlspecialchars($badge['points_required']) ?></p>
                        <?php if (in_array($badge['id'], $earnedBadges)): ?>
                            <button class="claimed" disabled>Claimed</button>
                        <?php elseif ($points >= $badge['points_required']): ?>
                            <form method="POST" action="claim_badge.php">
                                <input type="hidden" name="badge_id" value="<?= $badge['id'] ?>">
                                <button type="submit" class="claim">Claim</button>
                            </form>
                        <?php else: ?>
                            <button class="disabled" disabled>Claim</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</body>