<?php
session_start(); include("db.php"); include("functions.php"); require_login(); role_only('admin');
?><!DOCTYPE html>
<html><head><meta charset='utf-8'><title>Admin Panel</title><link rel='stylesheet' href='css/style.css'></head>
<body><div class='top'><div>Electricity Billing</div><div class='nav'>
<a href='dashboard.php'>Home</a> | <a href='logout.php'>Logout</a>
</div></div><div class='container'>
<h2>Admin Panel</h2>

<h3>Create User</h3>
<form method="POST">
  <input name="fullname" placeholder="Full name" required>
  <input name="username" placeholder="Username" required>
  <input name="password" type="text" placeholder="Password" required>
  <select name="role">
    <option value="admin">admin</option>
    <option value="biller">biller</option>
    <option value="consumer">consumer</option>
    <option value="manager">manager</option>
  </select>
  <button name="create_user">Create</button>
</form>
<?php
if(isset($_POST['create_user'])){
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $role     = $conn->real_escape_string($_POST['role']);
    if($conn->query("INSERT INTO users(fullname,username,password,role) VALUES('$fullname','$username','$password','$role')")){
        $uid = $conn->insert_id;
        if($role=='consumer'){
            $meter = 'M'.str_pad($uid,6,'0',STR_PAD_LEFT);
            $conn->query("INSERT INTO meters(user_id,meter_no) VALUES($uid,'$meter')");
        }
        echo "<div class='ok'>User created.</div>";
    } else echo "<div class='error'>".$conn->error."</div>";
}
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    echo "<div class='ok'>User deleted.</div>";
}
?>
<h3>All Users</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Username</th><th>Role</th><th>Action</th></tr>
<?php
$res=$conn->query("SELECT * FROM users ORDER BY id DESC");
while($r=$res->fetch_assoc()){
    echo "<tr><td>{$r['id']}</td><td>{$r['fullname']}</td><td>{$r['username']}</td><td>{$r['role']}</td><td><a class='btn' href='admin.php?delete={$r['id']}'>Delete</a></td></tr>";
}
?>
<h3>Complaints</h3>
<table>
<tr><th>ID</th><th>User</th><th>Message</th><th>Status</th><th>Reply</th></tr>
<?php
$res=$conn->query("SELECT c.*, u.fullname FROM complaints c JOIN users u ON u.id=c.user_id ORDER BY c.id DESC");
while($r=$res->fetch_assoc()){
  $reply = htmlspecialchars($r['reply'] ?? '');
  $openSel = $r['status']=='open' ? 'selected' : '';
  $closedSel = $r['status']=='closed' ? 'selected' : '';
  echo "<tr><td>{$r['id']}</td><td>{$r['fullname']}</td><td>{$r['message']}</td><td>{$r['status']}</td>
  <td>
    <form method='post' style='display:flex; gap:6px'>
      <input type='hidden' name='cid' value='{$r['id']}'>
      <input name='reply' placeholder='Type reply' value='$reply'>
      <select name='status'><option $openSel value='open'>open</option><option $closedSel value='closed'>closed</option></select>
      <button name='do_reply'>Save</button>
    </form>
  </td></tr>";
}
if(isset($_POST['do_reply'])){
    $cid=intval($_POST['cid']); $reply=$conn->real_escape_string($_POST['reply']); $status=$conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE complaints SET reply='$reply', status='$status' WHERE id=$cid");
    header("Location: admin.php"); exit();
}
?>
</div></body></html>