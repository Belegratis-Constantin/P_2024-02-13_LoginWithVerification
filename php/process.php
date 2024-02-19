<?php
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

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password_hashed = password_hash($password, PASSWORD_DEFAULT);

  // Insert data into the "users" table
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hashed')";

  if ($conn->query($sql) === TRUE) {
    header("Location: ../html/login.html");
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Close connection
$conn->close();
?>
