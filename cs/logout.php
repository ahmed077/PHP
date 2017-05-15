<?php
session_start();
require_once 'includes/init.php';
?>
    <h1 style="text-align: center">You Have Logged Out</h1>
    <h2 style="text-align: center">You Will Be Redirected To Homepage</h2>
<?php
session_reset();
session_destroy();
$_SESSION['userid'] = null;
$_SESSION['userdata'] = null;
redirect(3);
?>