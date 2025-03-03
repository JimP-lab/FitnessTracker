<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = 'localhost';
$dbname = 'fit';
$username = 'jim';
$password = '';


// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Send error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Database Connection Failed: ' . $e->getMessage()
    ]);
    exit();
}

// Determine request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request to retrieve user metrics
if ($method === 'GET') {
    // Check if username is provided
    if (!isset($_GET['username'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Username is required'
        ]);
        exit();
    }

    $username = $_GET['username'];

    try {
        // Prepare SQL to fetch user's metrics
        $stmt = $pdo->prepare("SELECT exercise_time, meals, sleep_duration, created_at 
                                FROM user_metrics 
                                WHERE username = :username 
                                ORDER BY created_at DESC");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch all metrics
        $metrics = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return metrics as JSON
        echo json_encode($metrics);
        exit();
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error retrieving metrics: ' . $e->getMessage()
        ]);
        exit();
    }
}

// Handle POST request to save new metrics
if ($method === 'POST') {
    // Get raw POST data
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    // Validate input
    if (!isset($data['username']) || 
        !isset($data['exercise_time']) || 
        !isset($data['meals']) || 
        !isset($data['sleep_duration'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Missing required fields'
        ]);
        exit();
    }

    $username = $data['username'];
    $exerciseTime = $data['exercise_time'];
    $meals = $data['meals'];
    $sleepDuration = $data['sleep_duration'];

    try {
        // Prepare SQL to insert new metrics
        $stmt = $pdo->prepare("INSERT INTO user_metrics 
                                (username, exercise_time, meals, sleep_duration, created_at) 
                                VALUES 
                                (:username, :exercise_time, :meals, :sleep_duration, NOW())");
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':exercise_time', $exerciseTime);
        $stmt->bindParam(':meals', $meals);
        $stmt->bindParam(':sleep_duration', $sleepDuration);
        
        // Execute the insert
        $stmt->execute();

        // Respond with success
        echo json_encode([
            'status' => 'success', 
            'message' => 'Metrics added successfully'
        ]);
        exit();
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error saving metrics: ' . $e->getMessage()
        ]);
        exit();
    }
}

// If method is not GET or POST
http_response_code(405);
echo json_encode([
    'status' => 'error', 
    'message' => 'Method Not Allowed'
]);
exit();
?>
