<?php
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure both 'subjects' and 'answers' are received
    if (isset($_POST['subjects']) && isset($_POST['answers']) && isset($_POST['answersValue'])) {
        // Decode the JSON strings back into PHP arrays
        $subjectsArray = json_decode($_POST['subjects'], true);
        $answerArray = json_decode($_POST['answers'], true);
        $answerValueArray = json_decode($_POST['answersValue'], true);

        if ($subjectsArray !== null && $answerArray !== null && $answerValueArray !== null) {
            // Process the arrays
            echo json_encode([
                'status' => 'success',
                'subjects' => $subjectsArray,
                'answers' => $answerArray,
                'answersValue' => $answerValueArray
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid JSON data'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing data'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
// Make a map of subjects and the scores to be updated
$final = array();
$numRows = 5;
for($i=0; $i<$numRows; $i++){
    $scoretoUpdate = 0;
    if($answerValueArray[$i] == 0){
        $scoretoUpdate = -50;
    }
    else if($answerValueArray[$i] == 1){
        $scoretoUpdate = -25;
    }
    else if($answerValueArray[$i] == 2){
        $scoretoUpdate = 25;
    }
    else if($answerValueArray[$i] == 3){
        $scoretoUpdate = 50;
    }

    $final[$subjectsArray[$i]] = $scoretoUpdate;
}

@$db = new mysqli('localhost', 'root', 'Marvin0101@', 'ecotrack');
$emailSel = $_SESSION['email'];
$query = 'SELECT category_scores FROM users WHERE email="' . $emailSel . '"';
$result = $db->query($query);
$categoryScoresArray = $result->fetch_assoc();
$categoryScoresArray = json_decode($categoryScoresArray['category_scores'], true); 

foreach ($final as $subject => $score) {
    // Check if the subject exists in the category scores and update the corresponding nested value
    foreach ($categoryScoresArray as $category => &$categoryData) {
        if (isset($categoryData[$subject])) {
            $categoryData[$subject] += $score;
        }
    }
}

// Re-encode the updated array into JSON
$updatedScores = json_encode($categoryScoresArray);

// Update the database with the new category scores
$updateQuery = 'UPDATE users SET category_scores = ? WHERE email="' . $emailSel . '"';
$stmt = $db->prepare($updateQuery);
$stmt->bind_param('s', $updatedScores);
$stmt->execute();

// Giving the user some points when they fill out the activity logger
$pointsQuery = 'SELECT points FROM users WHERE email="' . $emailSel . '"';
$result = $db->query($pointsQuery);
$points = $result->fetch_assoc();
$points = intval($points['points']);

// User gets AT LEAST 50 points when they complete the activity logger, more logic needed...
$updatedPoints = $points + 50;

$updatePointsQuery = 'UPDATE users SET points = ? WHERE email="' . $emailSel . '"';
$stmt = $db->prepare($updatePointsQuery);
$stmt->bind_param('i', $updatedPoints);
$stmt->execute();

$db->close();
?>
