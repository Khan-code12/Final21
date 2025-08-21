<?php
session_start(); include('db.php'); include('functions.php'); require_login(); role_only('manager');
?><!DOCTYPE html>
<html><head><meta charset='utf-8'><title>Manager Dashboard</title><link rel='stylesheet' href='css/style.css'></head>
<body><div class='top'><div>Electricity Billing</div><div class='nav'>
<a href='dashboard.php'>Home</a> | <a href='logout.php'>Logout</a>
</div></div><div class='container'>
<h2>Manager Dashboard</h2>

<?php
$users = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$bills = $conn->query("SELECT COUNT(*) c FROM bills")->fetch_assoc()['c'];
$paid  = $conn->query("SELECT COALESCE(SUM(amount),0) s FROM bills WHERE status='paid'")->fetch_assoc()['s'];
$unpaid  = $conn->query("SELECT COALESCE(SUM(amount),0) s FROM bills WHERE status='unpaid'")->fetch_assoc()['s'];
echo "<div class='cards'>
  <div class='card'>Users<br><b>$users</b></div>
  <div class='card'>Bills<br><b>$bills</b></div>
  <div class='card'>Collected<br><b>$paid</b></div>
  <div class='card'>Outstanding<br><b>$unpaid</b></div>
</div>";
?>
<h3>All Bills</h3>
<table>
<tr><th>ID</th><th>User</th><th>Units</th><th>Month</th><th>Amount</th><th>Status</th></tr>
<?php
$res=$conn->query("SELECT b.*, u.fullname FROM bills b JOIN users u ON u.id=b.user_id ORDER BY b.id DESC");
while($r=$res->fetch_assoc()){
  echo "<tr><td>{$r['id']}</td><td>{$r['fullname']}</td><td>{$r['units']}</td><td>{$r['month']}</td><td>{$r['amount']}</td><td>{$r['status']}</td></tr>";
}
?>
<h3>Transactions</h3>
<table>
<tr><th>ID</th><th>Bill ID</th><th>Amount</th><th>Date</th></tr>
<?php
$res=$conn->query("SELECT * FROM transactions ORDER BY id DESC");
while($r=$res->fetch_assoc()){
  echo "<tr><td>{$r['id']}</td><td>{$r['bill_id']}</td><td>{$r['amount']}</td><td>{$r['date']}</td></tr>";
}
?>
<h3>Users</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Username</th><th>Role</th></tr>
<?php
$res=$conn->query("SELECT id,fullname,username,role FROM users ORDER BY id DESC");
while($r=$res->fetch_assoc()){
  echo "<tr><td>{$r['id']}</td><td>{$r['fullname']}</td><td>{$r['username']}</td><td>{$r['role']}</td></tr>";
}
?>
</div></body></html>