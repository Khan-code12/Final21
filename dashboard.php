<?php
session_start();
if(!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$role = $_SESSION['user']['role'];
if($role == "admin") header("Location: admin.php");
elseif($role == "biller") header("Location: biller.php");
elseif($role == "consumer") header("Location: consumer.php");
elseif($role == "manager") header("Location: manager.php");
?>