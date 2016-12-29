<?php
$title = "Home Page";
if (isset($_COOKIE['logged'])) {
    session_start();
}
require_once 'base/basic.html';
require_once 'base/header.html';
echo "<div class='container-fluid'>";
if (isset($_COOKIE['logged'])) {
    echo '<h1>Welcome' . $_SESSION['name'] . '</h1>';
} else {
    echo '<h1>You Are Not Logged in/Registered user.<br/>
You can Register from <a href="registeration.php">Here</a><br/>
Or You can Login from <a href="login.php">Here</a></h1>';
}
echo "</div>";
require_once 'base/footer.html';
?>
