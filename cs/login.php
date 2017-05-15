<?php
session_start();
$title = "login";
require_once 'includes/init.php';
require_once 'includes/header.html';
if (isset($_SESSION['userid'])) {
    redirect();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $password = sha1($_POST['password']);
    $errors = [];
    $stmt = $con->prepare('SELECT * FROM students WHERE university_id = ?');
    $stmt->execute(array($id));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() > 0) {
        if ($password == $user['password']) {
            $_SESSION['userid'] = $id;
            $_SESSION['userdata'] = $user;
            redirect();
        } else {
            $errors[] = "Wrong Password";
        }
    } else {
        $errors[] = "This ID Doesn't Exits";
    }
}
?>
<h1 class="text-center text-primary">Login</h1>
<div class="clearfix"></div>
<form method="post" class="col-sm-6 col-sm-offset-3" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <?php if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
    }
    ?>
    <div class="form-group">
        <label class="control-label">ID:</label>
        <input name="id" type="text" class="form-control" placeholder="Your University ID">
    </div>
    <div class="form-group">
        <label class="control-label">Password:</label>
        <input name="password" type="password" class="form-control" placeholder="********">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-info btn-block" value="Login">
    </div>
</form>
<?php
require_once 'includes/footer.html';
?>