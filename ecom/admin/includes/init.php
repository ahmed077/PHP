<?php
require_once "includes/connect.php";
require_once 'includes/functions.php';
$includes = 'includes/';
$css = 'assets/css/';
$js = 'assets/js/';
$libs = 'assets/libs/';

require_once $includes . 'head.html';
if (!isset($nonavbar)) {
    require_once $includes . 'navbar-bs.html';
}
