<?php
session_start();

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "mydatabase";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process login form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $admin_username = $_POST["username"];
  $admin_password = $_POST["password"];

  // Check if the provided credentials match the admin record in the database
  $sql = "SELECT * FROM users WHERE username='$admin_username' AND admin = '1'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Authentication successful, set session variable
      if (password_verify($admin_password, $row['password'])) {
        $_SESSION["admin"] = $admin_username;
        header("Location: admin_dashboard.php");
        $result->free();
      } else {
        echo "Invalid password";
      }
    }
    exit();
  } else {
    echo "Invalid username or password";
  }
}

// Close connection
$conn->close();
?>
