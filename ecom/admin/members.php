<?php
/*
 *
 *
 */
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $title = 'Members';
    $action = isset($_GET['action'])?$_GET['action']:'manage';
    require_once 'includes/init.php';
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        if ($action == 'edit') {
            //edit member info
            if(isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $id=$_GET['userid'];
                try {
                    $stmt = $con->prepare("SELECT id, username, email, name FROM `members` WHERE id = ?");
                    $stmt->execute(array($id));
                    $userdata = $stmt->fetch();
                }
                catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                $userdata=$_SESSION['userdata'];
            }
            if($stmt->rowCount() != 0) {
                addHeading(); ?>
                <div class="container">
                    <form
                        class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0"
                        method="post" action="?action=update">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username: </label>
                            <div class="col-sm-10">
                                <input type="text" name="username" class="form-control input-lg"
                                       value="<?php echo $userdata['username']; ?>" required='required'
                                       autocomplete='off'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name: </label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<?php echo $userdata['name']; ?>"
                                       class="form-control input-lg" autocomplete='off' placeholder="Your Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password: </label>
                            <div class="col-sm-10">
                                <input type="password" name="password" placeholder="********"
                                       class="form-control input-lg"
                                       autocomplete='new-password'>
                                <input type="hidden" name="oldpassword" value="<?php echo $userdata['password']; ?>">
                                <input type="hidden" name="id" value="<?php echo $userdata['id']; ?>">
                                <i class="fa fa-eye passtoggle fa-2x"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email: </label>
                            <div class="col-sm-10">
                                <input type="text" name="email" value="<?php echo $userdata['email']; ?>"
                                       class="form-control input-lg" autocomplete='off'
                                       placeholder="Please Enter a Valid Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Changes" class="btn btn-primary center-block">
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            } else {redirect('Page Not Found', 'danger'); }
        }  elseif ($action == 'update') {
            //update member info in database
            $nofooter='';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                ?>
                <h1 class="text-center text-danger">Update <?php echo $title;?></h1>
                <?php
                $errors = array();
                $username = $_POST['username'];
                $name = $_POST['name'];
                $id = $_POST['id'];
                empty($_POST['password']) ? $password = $_POST['oldpassword'] : $password = $_POST['password'];
                $email = $_POST['email'];
                if (strlen($name) < 4 || strlen($name) > 20) {
                    $errors[] = "Name Error";
                }
                if (empty($email)) {
                    $errors[] = "Email Error";
                }
                if (!empty($errors)) {
                    echo '<div class="container">';
                    foreach ($errors as $err) {
                        echo '<div class=\'alert alert-danger\'>' . $err . '</div>';
                    }
                    echo '</div>';
                } else {
                    try {
                        $update = $con->prepare("UPDATE `members` SET `username` = ?, `name` = ? , `password` = ? , `email` = ? WHERE `id` = ?");
                        $update->execute(array($username, $name, sha1($password), $email, $id));
                        if ($id == $_SESSION['userdata']['id']) {
                            $_SESSION['userdata']['name'] = $name;
                            $_SESSION['userdata']['email'] = $email;
                        }
                        redirect("Successfully Updated", 'success');
                    } catch (PDOException $e) {
                        echo '<div class="container alert alert-danger">An Error Has Occured: ' . $e->getMessage() . '</div>';
                    }
                }
            } else {
                redirect('You Can\'t Access Here', 'danger');
            }
        } elseif ($action === 'insert') {
            //add member in database
            $nofooter ='';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                !empty($_POST['name']) ? $name = $_POST['name'] : $name = 'user';
                $errors = array();
                if (strlen($name) < 4 || strlen($name) > 20) {
                    $errors[] = "Name Error";
                }
                if (empty($email)) {
                    $errors[] = "Email Error";
                }
                if (empty($password)) {
                    $errors[] = "Password Error";
                }
                ?>
                <h1 class="text-center text-danger">Insert <?php echo $title;?></h1>
                <?php
                if (!empty($errors)) {
                    echo '<div class="container">';
                    foreach ($errors as $err) {
                        echo '<div class=\'alert alert-danger\'>' . $err . '</div>';
                    }
                    echo '</div>';
                } elseif (!checkDataBase('username', 'members', $username)) {
                    try {
                        $insert = $con->prepare("INSERT INTO `members` (`username`, `password`, `email`, `name`, `regDate`, `regStatus`) VALUES (?,?,?,?, now(), 1)");
                        $insert->execute(array($username, sha1($password), $email, $name));
                        redirect($insert->rowCount() . " Member Successfully Added", 'success');
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">Failed to add a member:<br/>' . $e->getMessage();
                    }
                } else {
                    redirect('username already exists', 'danger', $_SERVER['HTTP_REFERER']);
                }
            } else {
                redirect('You Can\'t Access this Page', 'danger');
            }
        } elseif ($action === 'add') {
            //add new member page
            addHeading(); ?>
            <div class="container">
                <form
                    class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0"
                    method="post" action="?action=insert">
<!--                    username field-->
                    <div class="form-group">
                        <label for="username" class="col-sm-2 control-label">Username: </label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control input-lg" required='required'
                                   autocomplete='off' placeholder="Username to login">
                        </div>
                    </div>
<!--                    name field-->
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name: </label>
                        <div class="col-sm-10">
                            <input type="text" name="name"
                                   class="form-control input-lg" autocomplete='off' placeholder="Your Name">
                        </div>
                    </div>
<!--                    password field-->
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password: </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" placeholder="********" class="form-control input-lg"
                                   autocomplete='new-password' required='required'>
                            <i class="fa fa-eye passtoggle fa-2x"></i>
                        </div>
                    </div>
<!--                    email field-->
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email: </label>
                        <div class="col-sm-10">
                            <input type="text" name="email"
                                   class="form-control input-lg" autocomplete='off' required='required'
                                   placeholder="Please Enter a Valid Email">
                        </div>
                    </div>
<!--                    submit button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-danger center-block">
                        </div>
                    </div>
                </form>
            </div>
            <?php
        } elseif ($action === 'manage') {
            $query = isset($_GET['page'])&&$_GET['page']=='pending'?"AND regStatus = 0":"";
            $show = $con->prepare("SELECT * FROM `members` WHERE groupID != 1 $query");
            $show->execute();
            $members = $show->fetchAll(PDO::FETCH_ASSOC);
            addHeading();
            ?>
            <?php if($show->rowCount() > 0) {?>
            <div class="container table-responsive">
                <table class="table table-bordered text-center members-table " style="margin:auto">
                    <tbody>
                    <?php
                    echo '<tr>';
                    foreach ($members[0] as $key => $val) {
                        if ($key == 'password') {continue;}
                        echo '<th class="text-center bg-danger">' . ucfirst($key) . '</th>';
                    }
                    echo '<th class="text-center bg-danger">Controls</th></tr>';
                    foreach ($members as $member) {
                        echo '<tr>';
                        foreach ($member as $key2 => $val) {
                            if ($key2 == 'password') {continue;}
                            echo '<td>' . ucfirst($val) . '</td>';
                        }
                        echo '<td>';
                        if($member['regStatus'] == 0) {
                            echo '<div class="btn btn-info"><a href="members.php?action=approve&userid=' . $member['id'] . '"><i class="fa fa-check"></i>Approve</a></div>';
                        }
                              echo '<div class="btn btn-success"><a href="members.php?action=edit&userid=' . $member['id'] . '"><i class="fa fa-edit"></i>Edit</a></div>
                                <div class="btn btn-danger"><a class="confirm" href="members.php?action=delete&userid=' . $member['id'] . '"><i class="fa fa-close"></i>Delete</a></div>
                              </td></tr>';
                    }
                if(!isset($_GET['page'])){
                    ?>
                    <tr><td colspan="100%"><div class="btn btn-primary"><a href="members.php?action=add"><i class="fa fa-plus"></i>New Member</a></div></td></tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <?php
            } else {
                redirect("No Pending Members", "info");
            }
        } elseif ($action=='delete') {
            if(isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $id = $_GET['userid'];
                $nofooter = '';
                if (checkDataBase('id', 'members', $id)) {
                    $stmt = $con->prepare("DELETE FROM `members` WHERE id = ?");
                    $stmt->execute(array($id));
                    redirect($stmt->rowCount() . ' Records Deleted', 'success', $_SERVER['HTTP_REFERER']);
                } else {redirect('Page Not Found', 'danger');}
            } else {redirect('Member Not Found', 'danger');}
        } elseif ($action=='approve') {
            if(isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $id = $_GET['userid'];
                if (checkDataBase('id', 'members', $id)) {
                    $stmt = $con->prepare("UPDATE `members` SET regStatus = 1 where id = ?");
                    $stmt->execute(array($id));
                    redirect($stmt->rowCount() . ' user approved', 'success', $_SERVER['HTTP_REFERER']);
                } else {redirect('Member Not Found', 'danger');}
            } else {redirect('Page Not Found', 'danger');}
        } else {redirect('Page Not Found', 'danger');}
    } else {
?>
        <h1 class="text-center text-danger">Manage Members</h1>
        <div class="container">
            <ul class="list-unstyled list-inline">
                <li><a href="?action=add">Add</a></li>
                <li><a href="?action=manage">Show All Members</a></li>
            </ul>
        </div>
        <!--members page-->
<?php
    }
    require_once 'includes/footer.html';
} else {
    redirect('You Must Login First', 'danger');
}
?>