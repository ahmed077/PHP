<?php
session_start();
$nonavbar = '';
$title = "Home Page";
    require_once 'includes/init.php';
    if (isset($_SESSION['userdata']['username'])) {
        header('Location: dashboard.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<div class="container alert alert-success">Home Page </br>';
        echo 'UserName: ' . $_POST['username'] . '</br>';
        echo 'Password: ' . $_POST['password'] . '</br>';
        echo 'hashed: ' . sha1($_POST['password']) . '</div>';
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed = sha1($password);
        //user check
        $stmt = $con->prepare("SELECT id, username, password, name, email FROM members WHERE username = ? AND password = ?");
        $stmt->execute(array($username, $hashed));
        $_SESSION['userdata'] = $stmt->fetch();
        $count = $stmt->rowCount(); // number of rows found
        if ($count > 0) {
            echo '<div class="container alert alert-danger">welcome ' . $_SESSION['userdata']['name'] .'</div>';
            $_SESSION['userdata']['username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            echo '<div class="container alert alert-danger"> This User Does Not Exist</div>';
        }
    } else {
        ?>
        <div class="container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="login col-sm-6 col-sm-offset-3">
                <div class='form-group'>
                    <input type='text' class='form-control' name="username" placeholder='username'>
                    <input type='password' class='form-control' name="password" autocomplete='new-password' placeholder='********'>
                    <input type='submit' class='btn btn-primary btn-block' value='LOGIN'>
                </div>
            </form>
        </div>
        <?php
    }
    require_once 'includes/footer.html';
?>