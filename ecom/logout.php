<?php
session_start();
if (isset($_SESSION['user'])) {
    $nonavbar = '';
    require_once 'includes/init.php';
    echo 'Goodbye ' . ucfirst(strtolower($_SESSION['name']));
    session_unset();
    session_destroy();
    header('refresh:5;url=index.php');
} else {
    header('Location: index.php');
}