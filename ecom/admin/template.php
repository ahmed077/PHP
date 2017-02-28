<?php
/*
 *
 *
 */
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $title='';
    require_once 'includes/init.php';
    $action=isset($_GET['action'])?$_GET['action']:'manage';
    if ($action == 'manage') {
        addHeading();
    } elseif($action == 'add') {
        addHeading();
    } elseif($action == 'insert') {
        addHeading();
    } elseif($action == 'edit') {
        addHeading();
    } elseif ($action == 'update') {
        addHeading();
    } elseif ($action == 'delete') {
        addHeading();
    } else {
        redirect('Page Not Found', 'danger');
    }
    require_once 'includes/footer.html';
} else {
    redirect("You Will Be Redirected in 3 Seconds", "info");
}