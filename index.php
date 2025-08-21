<?php session_start(); include("db.php"); ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Electricity Billing - Login</title>
<link rel="stylesheet" href="css/style.css"></head><body>
<div class="container"><h2>Login</h2>
<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
<p>New consumer? <a href="register.php">Apply for new connection</a></p>
<?php
if(isset($_POST['login'])){
  $username = $conn->real_escape_string($_POST['username']);
  $password = $conn->real_escape_string($_POST['password']);
  $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
  $res = $conn->query($sql);
  if($res && $res->num_rows){
    $row = $res->fetch_assoc();
    if($row['password'] === $password){ $_SESSION['user'] = $row; header("Location: dashboard.php"); exit(); }
    else echo "<div class='error'>Invalid password!</div>";
  } else echo "<div class='error'>User not found!</div>";
}
?>
</div></body></html>