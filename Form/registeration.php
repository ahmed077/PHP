<?php
$title = "Registeration Page";
if (isset($_COOKIE['logged'])) {
    session_start();
}
?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>Registeration Page</title>-->
<!--    <link rel="stylesheet" href="css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="css/main.css">-->
<!--    <link rel="stylesheet" href="css/registeration.css">-->
<!--</head>-->
<!--<body>-->

<?php require_once 'base/basic.html';?>
    <?php require_once 'base/header.html';?>
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
            echo "<div class='container'>
                    <div class='row'>
                        <div class='col-sm-12 text-xs-center'>
                            Thank You for Registering
                        </div>";
            echo '<div class=\'col-sm-12 text-xs-center\'>Name: ' . $name . '</div>';
            echo '<div class=\'col-sm-12 text-xs-center\'>password: ' . $password . '</div>';
            echo '<div class=\'col-sm-12 text-xs-center\'>email: ' . $email . '</div>';
            echo '<div class=\'col-sm-12 text-xs-center\'>Gender Is: ' . $gender . '</div>';
            echo '<div class=\'col-sm-12 text-xs-center\'>Message: ' . $message . '</div></div></div>';
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