<?php
function redirect($time = 0,$page = 'index.php') {
    header("REFRESH:$time;url=$page");
}
function getFromDB($item,$table,$w = '',$conditions=null) {
    global $con;
    $stmt = $con->prepare("SELECT $item FROM $table $w");
    $stmt->execute(array($conditions));
    return ($stmt->rowCount() > 0)? $stmt->fetch(PDO::FETCH_ASSOC) : false;
}