<?php
$title = "Registeration Page";
if (isset($_COOKIE['logged'])) {
    session_start();
}
require_once 'base/basic.html';
require_once 'base/header.html';
?>
    <h1 class="text-center text-danger">Registeration</h1>
    <?php
    $exist = false;
    if (isset($_SESSION['logged'])) {
        echo "<div class=\"container text-xs-center\">You are not authorized to be here</div>";
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        /*todo > add layout*/
        if (!file_exists(__DIR__ . '/db/' . $name . '.txt')) {
            $password = $_POST['password'];
            $gender = $_POST['gender'];
            $message = $_POST['message'];
            echo "
<div class=\"container text-xs-center\">Thank You for Registering<br/>
You Will be Redirected to Home page in 
<span id='timer'>5</span></div>
<script>
i = 4;
setInterval(function () {
    document.getElementById('timer').textContent = i;
    if (i == 0) {
        location = \"home.php\";
        clearInterval();
    }
    i--;
}, 1000);</script>
";
            if (!is_dir(__DIR__ . "/db")) {
                mkdir("db");
            }
            $fileA = fopen(__DIR__ . '/db/' . $name . '.txt', 'x+');
            $data = fwrite($fileA,
                'UserName: ' . $name . ".\r\n" .
                'Password: ' . $password . ".\r\n" .
                'Email: ' . $email . ".\r\n" .
                'Gender: ' . $gender . ".\r\n" .
                'Message: ' . $message . ".\r\n");
        } else {
            $exist = true;
            require_once "base/form.html";
        }
    } else {
        require_once "base/form.html";
    }
    ?>
    <?php require_once "base/footer.html";?>
<script src="js/jquery.min.js"></script>
<script src="js/main.js"></script>
<script src="js/register.js"></script>
</body>
</html>