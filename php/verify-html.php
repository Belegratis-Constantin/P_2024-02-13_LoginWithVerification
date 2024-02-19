<?php
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

if (isset($_GET['token'])) {
  // Get the token from the URL
  $token = $_GET['token'];

  $sql = "SELECT * FROM users WHERE verification_token = '$token'";
  $result = $mysqli->query($sql);

  if ($result->num_rows > 0) {
    // Fetch the data from the result object
    $row = $result->fetch_assoc();

    if ($row['verified'] == 0) {
      // Delete the user if not verified
      $sql_delete = "DELETE FROM users WHERE verification_token = '$token'";
      if ($mysqli->query($sql_delete)) {
        echo "User deleted successfully.";
        header("Location: ../index.html");
        exit(); // Exit to prevent further execution
      } else {
        echo "Error deleting user: " . $mysqli->error;
      }
    } else {
      echo "User is verified.";
    }
  } else {
    echo "User with token $token not found.";
  }
} else {
  echo "Token not found in the URL.";
}
?>
