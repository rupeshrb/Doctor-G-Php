<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$database = "doctor";

// Establishing the database connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch suggestions based on user input
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT name FROM diseases WHERE name LIKE '%$query%' LIMIT 5"; // Use LIKE for partial string match
    $result = mysqli_query($conn, $sql);

    $suggestions = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row['name'];
    }

    // Return suggestions as JSON
    echo json_encode($suggestions);
}

// Close the database connection
mysqli_close($conn);
?>
