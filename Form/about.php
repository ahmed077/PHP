<?php
$title = "About Page";
if (isset($_COOKIE['logged'])) {
    session_start();
}
require_once 'base/basic.html';
require_once 'base/header.html';
echo "<div class=\"container text-xs-center h1\">";
if (isset($_COOKIE['logged'])) {
    $file_dir = __DIR__ . '\db\\' . strtolower($_SESSION['name']) . '.txt';
    $file = fopen($file_dir, 'r');
    $data = fread($file, filesize($file_dir));
    $data = explode('.', $data);
    echo "Your Info: <div class='text-xs-left'>";
    foreach ($data as $info) {
        echo $info . '<br/>';
    }
    echo "</div>";
} else {
    echo "Only Registered Members Can View This Page";
}
echo "</div>";
require_once 'base/footer.html';
?>