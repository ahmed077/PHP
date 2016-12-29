<?php
if (isset($_COOKIE['logged'])) {
    session_start();
}
$exist = true;
echo " 
<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\"
          content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>Login</title>
    <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"css/main.css\">
    <link rel=\"stylesheet\" href=\"css/login.css\">
</head>
<body>";
if (!isset($_COOKIE['logged']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $password = $_POST['password'];
    $file_dir = __DIR__ . '\db\\' . strtolower($name) . '.txt';
    if (file_exists($file_dir)) {
        $file = fopen($file_dir, 'r');
        $content = fread($file, filesize($file_dir));
        $start = stripos($content, 'password: ') + 10;
        $end = stripos($content, '.', $start) - $start;
        $result = substr($content, $start, $end);
        if ($result == $password) {
            $exist = true;
            session_start();
            setcookie('logged', true, time() + 60, '/');
            $_SESSION['name'] = $name;
            $_SESSION['logged'] = true;
        } else {
            $exist = false;
        }
    } else {
        $exist = false;
    }
}
require_once "base/header.html";
echo "<div class=\"container\">";
if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    echo "<span class='d-block text-danger text-xs-center bg-warning'>Welcome Back " . ucfirst($_SESSION['name']) . "</span>";
} else {
    require_once "base/login.html";
}
echo "</div>";
require_once "base/footer.html";
echo "<script src=\"js/jquery.min.js\"></script>
<script src=\"js/login.js\"></script>
</body>
</html>";
?>