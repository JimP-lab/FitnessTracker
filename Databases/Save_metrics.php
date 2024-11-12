<?php
$servername = 'localhost';
$username = 'fit';
$password = 'root';
$dbname = '';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle different requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it is a save request
    if (isset($_POST['exercise_time'], $_POST['meals'], $_POST['sleep_duration'])) {
        $exercise_time = $_POST['exercise_time'];
        $meals = $_POST['meals'];
        $sleep_duration = $_POST['sleep_duration'];

        $sql = "INSERT INTO metrics (exercise_time, meals, sleep_duration) VALUES ('$exercise_time', '$meals', '$sleep_duration')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Metrics were saved successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $conn->error]);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle fetch request to retrieve all metrics
    $result = $conn->query("SELECT * FROM metrics ORDER BY id DESC");
    $metrics = [];

    while ($row = $result->fetch_assoc()) {
        $metrics[] = $row;
    }

    echo json_encode($metrics);
}

$conn->close();
?>
