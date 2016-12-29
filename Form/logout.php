<?php
if (isset($_COOKIE['logged'])) {
    session_start();
}
echo "
<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\"
          content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>Logout</title>
    <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"css/main.css\">
</head>
<body>";
require_once "base/header.html";
    if (isset($_COOKIE['logged']) && $_COOKIE['logged']) {
        echo "<div class=\"container text-xs-center\">You have been Logged out "  . ucfirst($_SESSION['name']) . "</div>";
        session_unset();
        @session_destroy();
        setcookie('logged', "", time() - 3600, '/');
        setcookie('name', "", time() - 3600, '/');
        echo "<div class=\"container text-xs-center\">You will be Redirected in <span id='timer'>5</span></div>
<script>
i = 4;
setInterval(function () {
    document.getElementById('timer').textContent = i;
    if (i == 0) {
        history.back();
        clearInterval();
    }
    i--;
}, 1000);</script>";
    } else {
        echo "<div class=\"container text-xs-center\">You are not authorized to be here</div>";
    }
require_once "base/footer.html";
echo "
</body>
</html>";
?>
