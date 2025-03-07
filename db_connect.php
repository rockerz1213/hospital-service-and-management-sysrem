<?php
// Database connection details
$host = "localhost";      // Your MySQL server (localhost if on the same server)
$username = "root";       // Database username (usually 'root' for local servers)
$password = "";           // Database password (empty for XAMPP/WAMP default)
$dbname = "appointment_system"; // The database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Connection successful
    // echo "Connected successfully";
}
?>
