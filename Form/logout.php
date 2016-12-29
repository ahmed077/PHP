<?php
$title = "Logout";
if (isset($_COOKIE['logged'])) {
    session_start();
}
require_once "base/basic.html";
require_once "base/header.html";
    if (isset($_COOKIE['logged']) && $_COOKIE['logged']) {
        echo "<div class=\"container text-xs-center\"><h2>You have been Logged out "  . ucfirst($_SESSION['name']) . "</h2>";
        session_unset();
        @session_destroy();
        setcookie('logged', "", time() - 3600, '/');
        setcookie('name', "", time() - 3600, '/');
        echo "<h2>You will be Redirected in <span id='timer'>5</span></h2></div>
<script>
i = 4;
setInterval(function () {
    document.getElementById('timer').textContent = i;
    if (i == 0) {
        window.location = \"home.php\";
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
