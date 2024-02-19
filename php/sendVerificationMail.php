<?php
require '../json/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random verification token
function generateToken($length = 32) {
  return bin2hex(random_bytes($length));
}

$host = "localhost";
$username = "root";
$password = "";
$database = "mydatabase";

// Connect to the database (replace with your database credentials)
$mysqli = new mysqli($host, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Collect user information (replace with your form input handling)
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Generate verification token
$token = generateToken();

// Store user information in the database
$sql = "INSERT INTO users (username, email, password, verification_token, verified) VALUES ('$username', '$email', '$password_hashed', '$token', 0)";
if ($mysqli->query($sql) === TRUE) {
  // Send verification email
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = ''; // SMTP server. change to your smtp-host (google: 'smtp.google.com')
  $mail->SMTPAuth = true;
  $mail->Username = ''; // SMTP username. Your email address
  $mail->Password = ''; // SMTP password. Set to your password (with google create a App-Password and insert this)
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->setFrom('', 'test test'); // Insert your email address at ''
  $mail->addAddress($email, $username);
  $mail->isHTML(true);
  $mail->Subject = 'Email Verification';
  $mail->Body    = 'Please click the following link to verify your email address: <a href=".../php/verify.php?token='.$token.'">Verify Email</a>'; // cahange the '.../' part to the local path on your computer so you reach the php/verify.php file

  // Enable debugging
  $mail->SMTPDebug = SMTP::DEBUG_SERVER;

  if(!$mail->send()) {
    echo 'Email could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
    echo 'A verification link has been sent to your email address. Please verify your email to complete the registration.';
    header("Location: ../html/verify.php?token=$token");
    }
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>
