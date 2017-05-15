<?php
$dsn = 'mysql:host=localhost;dbname=cs';
$user = 'root';
$pass = '';
$connectionstatus = false;
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connectionstatus = true;
}
catch(PDOException $e) {
    $connectionstatus = false;
    $errmessage = $e->getMessage();
}
require_once 'functions.php';