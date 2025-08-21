<?php session_start(); include('db.php'); ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Apply New Connection</title><link rel="stylesheet" href="css/style.css"></head>
<body><div class="container">
<h2>New Consumer Registration</h2>
<form method="POST">
  <input name="fullname" placeholder="Full name" required>
  <input name="username" placeholder="Choose username" required>
  <input name="password" type="text" placeholder="Choose password" required>
  <button name="register">Register</button>
</form>
<a href="index.php">Back to Login</a>
<?php
if(isset($_POST['register'])){
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    if($conn->query("INSERT INTO users(fullname,username,password,role) VALUES('$fullname','$username','$password','consumer')")){
        $uid = $conn->insert_id;
        $meter = 'M'.str_pad($uid,6,'0',STR_PAD_LEFT);
        $conn->query("INSERT INTO meters(user_id,meter_no) VALUES($uid,'$meter')");
        echo "<div class='ok'>Registration successful. You can login now.</div>";
    } else echo "<div class='error'>".$conn->error."</div>";
}
?>
</div></body></html>