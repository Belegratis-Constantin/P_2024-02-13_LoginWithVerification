<?php
// Include the necessary PHPMailer classes
require '../json/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to connect to the database and verify the token
function verifyEmail($token) {
  $host = "localhost";
  $username = "root";
  $password = "";
  $database = "mydatabase";

  // Connect to the database
  $mysqli = new mysqli($host, $username, $password, $database);

  // Check the connection
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  // Escape the token to prevent SQL injection
  $token = $mysqli->real_escape_string($token);

  // Query to find the user with the provided token
  $sql = "SELECT * FROM users WHERE verification_token = '$token' AND verified = 0";

  // Execute the query
  $result = $mysqli->query($sql);

  // Check if a user with the token exists
  if ($result->num_rows > 0) {
    // User found, update the 'verified' field to mark the email as verified
    $updateSql = "UPDATE users SET verified = 1 WHERE verification_token = '$token'";
    if ($mysqli->query($updateSql) === TRUE) {
      echo "Email verified successfully. You can now log in.";
      header('Refresh: 2; ../html/login.html');
    } else {
      echo "Error updating record: " . $mysqli->error;
    }
  } else {
    echo "Invalid verification token. Please try again.";
  }

  // Close the database connection
  $mysqli->close();
}

// Check if a token is provided in the URL
if (isset($_GET['token'])) {
  // Get the token from the URL
  $token = $_GET['token'];

  // Verify the email with the provided token
  verifyEmail($token);
} else {
  // No token provided in the URL
  echo "Token not found in the URL.";
}
?>
