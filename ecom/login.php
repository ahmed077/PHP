<?php
session_start();
$title='Login / Signup';
if(isset($_SESSION['user'])){header('Location:index.php');exit();}
require_once 'includes/init.php';
//Check User
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['email'])) {
        $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        $errors = array();
        if(checkDataBase('username','members',$user)){$errors[] = 'Username Exists';}
        if(checkDataBase('email','members',$email)){$errors[] = 'Email Exists';}
        if(empty($password)){$errors[] = 'You must Type Password';}
        if(empty($errors)) {
            $insert = $con->prepare("INSERT INTO `members` (`username`, `password`, `email`, `regDate`, `regStatus`) VALUES (?,?,?, now(), 0)");
            $insert->execute(array($user, sha1($password), $email));
            $success = 'Successfully Registered, Waiting Admin\'s Approval';
        } else {
            $error_found = true;
        }
    } else {
        $user = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $errors = array();
        if(empty($user)){'You must Type Your Username';}
        if(empty($password)){'You must Type Your Password';}
        $stmt = $con->prepare('SELECT * FROM members WHERE username=? AND password=?');
        $stmt->execute(array($user, sha1($password)));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            $_SESSION['user'] = $user;
            $_SESSION['id'] = $data['id'];
            $_SESSION['name'] = $data['name'];
            header('Location:index.php');
            exit();
        } else {
            if(!checkDataBase('username', 'members', $user)){$errors[] = 'Username Not Found!';}
            else {$errors[] = 'Username/Password is Wrong';}
        }
    }
}
if(!isset($success)){
?>
    <h1 class="text-center text-danger login-header"><span data-target=".login" class="bold">Login</span> | <span data-target=".signup">Sign up</span></h1>
        <div class="container">
<!--            Login Form-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-0 login">
                <div class='form-group'>
                    <input type='text' class='form-control' name="username" placeholder='username'>
                    <input type='password' class='form-control' name="password" autocomplete='new-password' placeholder='********'>
                    <input type='submit' class='btn btn-primary btn-block' value='LOGIN'>
                </div>
            </form>
<!--            Sign up Form-->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-0 signup">
                <div class='form-group'>
                    <input type='text' class='form-control' name="username" placeholder='username' required="required">
                </div>
                <div class="form-group">
                    <input type='password' class='form-control' name="password" autocomplete='new-password' placeholder='********' required="required">
                    <i class="fa fa-eye-slash passtoggle" aria-hidden="true"></i>
                </div>
                <div class="form-group">
                    <input type='email' class='form-control' name="email" placeholder='Email' required="required">
                </div>
                <div class="form-group">
                    <input type='submit' class='btn btn-success btn-block' value='SIGN UP'>
                </div>
            </form>
        </div>
    <?php
    if (!empty($errors)){
        echo '<div class="container errors">';
        foreach($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        echo '</div>';
    }
    ?>
<?php } else {
    redirect($success, 'success');
}
    require_once 'includes/footer.html'; ?>