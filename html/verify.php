<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify your email address</title>
</head>
<body>

<h2>Verify first</h2>

<p>Please verify your email in the next 30 seconds. Then you can <a href="../html/login.html">login</a>.</p>
<p>Do not close this window!</p>

<script>
  // Delay the redirection by 30 seconds
  setTimeout(function() {
    window.location.href = '../php/verify-html.php<?php if(isset($_GET['token'])) { echo "?token=" . $_GET['token']; } ?>';
  }, 30000); // 30 seconds in milliseconds
</script>


</body>
</html>
