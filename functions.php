<?php
function is_logged_in(){ return isset($_SESSION['user']); }
function require_login(){ if(!is_logged_in()){ header("Location: index.php"); exit(); } }
function role_only($r){ if($_SESSION['user']['role'] !== $r){ header("Location: dashboard.php"); exit(); } }
function calculateBill($units){
    if($units <= 100) $rate = 3.5;
    elseif($units <= 300) $rate = 4;
    elseif($units <= 500) $rate = 5;
    else $rate = 7;
    $demand_charge = 50;
    $amount = $units * $rate + $demand_charge;
    if($units <= 500) $amount += ($amount * 0.05);
    else $amount += ($amount * 0.10);
    return round($amount,2);
}?>