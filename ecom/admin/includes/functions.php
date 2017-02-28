<?php
// Redirect to a specific page
// Redirect to home page by default
function redirect($msg, $type='danger',$url='index.php', $seconds=3) {
    echo '<div class="container redirect">
            <div class="alert alert-' . $type . '">' . $msg . '</div>
            <div class="alert alert-info">You Will Be Redirected in ' . $seconds . ' Seconds</div>
         </div>';
    header("refresh:$seconds;url=$url");
    exit();
}
// check for an item whether it exists in database with a specific value
// true if exists
function checkDataBase($item, $table, $val){
    global $con;
    $statment = $con->prepare("SELECT $item FROM $table WHERE $item=?");
    $statment->execute(array($val));
    return $statment->rowCount() > 0;
}
// Get number of items in a specific column
// q1,q2,q3,q4 Optional Conditions
// q1 must start with "WHERE"
// q2~q4 must start with "AND"
function countItems($item, $table, $q1='', $q2='', $q3='', $q4='') {
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table $q1 $q2 $q3 $q4");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}
/*
 * Get Latest Records Function
 * Item > item to be checked
 */
function getLatest($item, $table, $order = 'regDate', $limit = 5, $q1='', $q2='') {
    global $con;
    $stmt2 = $con->prepare("SELECT $item FROM $table $q1 $q2 ORDER BY $order DESC LIMIT $limit");
    $stmt2->execute();
    return $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
/*
 * Check Status of Active or not
 */
function checkStatus($param) {
    if ($param == 1 ) {
        return '<i class="fa fa-toggle-on pull-right text-success"></i>';
    } else {
        return '<i class="fa fa-toggle-off pull-right text-danger"></i>';
    }
}
/*
 * Put A Heading With Title & Action
 */
function addHeading() {
    global $title;
    global $action;
    $new = ($action == 'add')?' New':'';
    if($title === 'Categories' && $action != 'manage') {$title2 = 'category';}
    elseif ($title !== 'Categories'&& $action !== 'manage') { $title2 = substr($title, 0, -1);}
    else {$title2 = $title;}
    $act = isset($_GET['page'])?$_GET['page']:$action;
    $title2 = ($action != 'manage')?substr($title, 0, -1):$title;
    echo "<h1 class=\"text-center text-danger\">" . ucfirst($act) . $new . " " . ucfirst($title2) . "</h1>";
    return null;
}
/*
TODO List
*********
Function Get Count or Check if Exists
Parameters:
Query > What comes after SELECT
Table > Table to Look in
q1~q4 optional conditions
q1 must start with "WHERE"
q2~q4 must start with "AND"
*************************************
*/