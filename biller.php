<?php
session_start(); include('db.php'); include('functions.php'); require_login(); role_only('biller');
?><!DOCTYPE html>
<html><head><meta charset='utf-8'><title>Biller Panel</title><link rel='stylesheet' href='css/style.css'></head>
<body><div class='top'><div>Electricity Billing</div><div class='nav'>
<a href='dashboard.php'>Home</a> | <a href='logout.php'>Logout</a>
</div></div><div class='container'>
<h2>Biller Panel</h2>

<form method="POST">
  <input type="number" name="uid" placeholder="Consumer User ID" required>
  <input type="number" name="units" placeholder="Units" required>
  <input type="text" name="month" placeholder="Month e.g. 2025-08" required>
  <button name="generate">Generate Bill</button>
</form>
<?php
if(isset($_POST['generate'])){
    $uid = intval($_POST['uid']);
    $units = intval($_POST['units']);
    $month = $conn->real_escape_string($_POST['month']);
    $amount = calculateBill($units);
    if($conn->query("INSERT INTO bills(user_id,units,month,amount) VALUES($uid,$units,'$month',$amount)")){
        echo "<div class='ok'>Bill generated. Amount: $amount</div>";
    } else echo "<div class='error'>".$conn->error."</div>";
}
?>
<h3>Recent Bills</h3>
<table>
<tr><th>ID</th><th>User</th><th>Units</th><th>Month</th><th>Amount</th><th>Status</th></tr>
<?php
$res=$conn->query("SELECT b.*, u.fullname FROM bills b JOIN users u ON u.id=b.user_id ORDER BY b.id DESC LIMIT 20");
while($r=$res->fetch_assoc()){
  echo "<tr><td>{$r['id']}</td><td>{$r['fullname']}</td><td>{$r['units']}</td><td>{$r['month']}</td><td>{$r['amount']}</td><td>{$r['status']}</td></tr>";
}
?>
</div></body></html>