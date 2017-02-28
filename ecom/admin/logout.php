<?php
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $nonavbar = '';
    require_once 'includes/init.php';
    echo 'Goodbye ' . ucfirst(strtolower($_SESSION['userdata']['name']));
    session_unset();
    session_destroy();
    header('refresh:5;url=index.php');
} else {
    header('Location: index.php');
}