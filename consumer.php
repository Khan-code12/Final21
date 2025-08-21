<?php
session_start(); include('db.php'); include('functions.php'); require_login(); role_only('consumer');
$uid = intval($_SESSION['user']['id']);
?><!DOCTYPE html>
<html><head><meta charset='utf-8'><title>Consumer Dashboard</title><link rel='stylesheet' href='css/style.css'></head>
<body><div class='top'><div>Electricity Billing</div><div class='nav'>
<a href='dashboard.php'>Home</a> | <a href='logout.php'>Logout</a>
</div></div><div class='container'>
<h2>Consumer Dashboard</h2>

<h3>Your Bills</h3>
<table>
<tr><th>ID</th><th>Month</th><th>Units</th><th>Amount</th><th>Status</th><th>Action</th></tr>
<?php
$res=$conn->query("SELECT * FROM bills WHERE user_id=$uid ORDER BY id DESC");
while($r=$res->fetch_assoc()){
  $payBtn = $r['status']=='unpaid' ? "<a class='btn' href='consumer.php?pay={$r['id']}'>Pay</a>" : "-";
  echo "<tr><td>{$r['id']}</td><td>{$r['month']}</td><td>{$r['units']}</td><td>{$r['amount']}</td><td>{$r['status']}</td><td>$payBtn</td></tr>";
}
if(isset($_GET['pay'])){
    $bid=intval($_GET['pay']);
    $billRes=$conn->query("SELECT * FROM bills WHERE id=$bid AND user_id=$uid AND status='unpaid'");
    if($billRes && $billRes->num_rows){
        $bill=$billRes->fetch_assoc();
        $amount=$bill['amount'];
        $conn->query("INSERT INTO transactions(bill_id,amount) VALUES($bid,$amount)");
        $conn->query("UPDATE bills SET status='paid' WHERE id=$bid");
        header("Location: consumer.php"); exit();
    }
}
?>
<h3>Submit a Complaint</h3>
<form method="POST">
  <textarea name="message" placeholder="Write your complaint..." required></textarea>
  <button name="submit_complaint">Submit</button>
</form>
<h3>Your Complaints</h3>
<table>
<tr><th>ID</th><th>Message</th><th>Status</th><th>Reply</th></tr>
<?php
if(isset($_POST['submit_complaint'])){
  $msg = $conn->real_escape_string($_POST['message']);
  $conn->query("INSERT INTO complaints(user_id,message) VALUES($uid,'$msg')");
  header("Location: consumer.php"); exit();
}
$res=$conn->query("SELECT * FROM complaints WHERE user_id=$uid ORDER BY id DESC");
while($r=$res->fetch_assoc()){
  $reply = htmlspecialchars($r['reply'] ?? '');
  echo "<tr><td>{$r['id']}</td><td>{$r['message']}</td><td>{$r['status']}</td><td>$reply</td></tr>";
}
?>
</div></body></html>