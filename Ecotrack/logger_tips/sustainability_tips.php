<head>
    <title>Sustainability Tips</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../resources/headerstyles.css">
    <script src="../resources/dropdown.js" defer></script>
    <link rel="stylesheet" href="sustainability_tips.css">
    <script type ="text/JavaScript" src="../resources/jquery-3.1.1.min.js"></script>
    <script type="text/JavaScript" src="tips_controller.js"></script>

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
        <li class="menuButton"><a href="../game_and_interactive/badges.php">Badges</a></li>
        <li class="menuButton"><a href="../game_and_interactive/leaderboard.php">Leaderboard</a></li>
        <li class="menuButton"><a href="./activity_logger.html">Activity Logger</a></li>
        <li class="menuButton"><a href="./sustainability_tips.php">Sustainability Tips</a></li>
        <p class="logOut">Log Out</p>
    </div>
</head>

<h1>Your Sustainability Tips</h1>

<div class= "no-tip-container"></div>

<div class = "total-tip-container">
    <div class="tip-container-1"></div>
    <div class="tip-container-2"></div>
    <div class="tip-container-3"></div>
</div>

<?php
session_start();
@$db = new mysqli('localhost', 'root', 'Marvin0101@', 'ecotrack');
$emailSel = $_SESSION['email'];
$query = 'SELECT category_scores FROM users WHERE email="' . $emailSel . '"';
$result = $db->query($query);

if ($result === false) {
    // Query failed - check for database errors
    die("Query error: " . $db->error);
}

if ($result->num_rows === 0) {
    // No matching record found
    die("No user found with that username");
}

$record = $result->fetch_assoc();

$badSubjects = [];
$categoryScores = json_decode($record['category_scores'], true);

// Iterate through the categories and subjects
$allLowScores = [];
foreach ($categoryScores as $category => $subjects) {
    foreach ($subjects as $subject => $score) {
        if ($score < 0) {
            $allLowScores[$subject] = $score;
        }
    }
}

asort($allLowScores);
$lowestScores = array_slice($allLowScores, 0, 3, true);

foreach($lowestScores as $subject => $score){
    $badSubjects[] = $subject; 
}

echo "<script>
    const badSubjects = " . json_encode($badSubjects) . ";
</script>";
?>