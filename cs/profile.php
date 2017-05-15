<?php
session_start();
require_once 'includes/init.php';
require_once 'includes/header.html';
if (isset($_GET['action'])) {
    if($_GET['action'] == 'edit') {
        $title = 'Edit Profile'; ?>
        <h2 class="text-center text-primary">Edit Your Info</h2>
        <form class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2 col-xs-offset-0" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?action=update';?>">
            <div class="form-group">
                <label class="control-label">ID</label>
                <input disabled readonly type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="name" class="control-label">Name</label>
                <input required id="name" value="<?php echo $_SESSION['userdata']['name'];?>" name="name" type="text" class="form-control" placeholder="Your Name">
            </div>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <input required id="email" value="<?php echo $_SESSION['userdata']['email'];?>" name="email" type="email" class="form-control" placeholder="Your New Email">
            </div>
            <div class="form-group">
                <label for="phone" class="control-label">Phone</label>
                <input required type="text" value="<?php echo $_SESSION['userdata']['phone'];?>" id="phone" placeholder="01xxxxxxxxx" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label for="newpassword" class="control-label">New Password</label>
                <input type="password" name="newpassword" id="newpassword" placeholder="********" class="form-control">
                <div class="alert alert-warning">Leave Empty to Keep the Old Password</div>
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-success" value="Edit">
            </div>

        </form>
<?php   } elseif ($_GET['action'] == 'update') {
            $newpw = !empty($_POST['newpassword'])? sha1($_POST['newpassword']) : $_SESSION['userdata']['password'];
            $stmt = $con->prepare("UPDATE `students` SET name=?,phone=?,email=?,password=?");
            $stmt->execute(array($_POST['name'],$_POST['phone'],$_POST['email'],$newpw));
        echo'<pre>';print_r($_SESSION['userdata']);echo'</pre>';

        $_SESSION['userdata'] = getFromDB('*','students','WHERE `university_id` = ?',$_SESSION['userid']);
            redirect(0,'profile.php');
        echo'<pre>';print_r($_SESSION['userdata']);echo'</pre>';
        }
    } else {
    $title = "profile";
    if (!empty($_SESSION['userdata']['Subjects'])) {
        $subjects_id = explode(',', $_SESSION['userdata']['Subjects']);
        $subjects = [];
        foreach ($subjects_id as $i) {
            $subjects[] = getFromDB('*', 'subjects', 'WHERE id = ?', $i);
        }

    }
    ?>
    <h1 class="text-center">My Information</h1>

    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="pull-left">Subjects</h4>
                    <a class="pull-right" href="subjects.php?action=add">
                        <div class="btn btn-warning">Add</div>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body"><?php
                    echo '<div class="row">';
                    foreach ($subjects[0] as $k => $s) {
                        if ($k == 'id' || $k == 'max_students') {continue;}
                        echo '<div class="col-xs-4">' . ucfirst($k) . '</div>';
                    }
                    echo '</div>';
                    foreach ($subjects as $subject) {
                        echo '<div class="row">
                            <div class="col-xs-4">' . ucfirst($subject['name']) . '</div>
                            <div class="col-xs-4">' . $subject['min_GBA'] . '</div>
                            <div class="col-xs-4">' . $subject['current_students_count'] . '</div>
                          </div>';
                    }
                    ?></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="pull-left">My Information</h4>
                    <a class="pull-right" href="profile.php?action=edit">
                        <div class="btn btn-warning">Edit</div>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($_SESSION['userdata'] as $k => $d) {
                        if ($k == 'id' || $k == 'password') {continue;}
                        if ($k == 'Subjects') {
                            $d = count($subjects_id);
                        }
                        echo '<div class="row">
                                <div class="col-xs-3">' . ucfirst($k) . '</div><div class="col-xs-9">' . $d . '</div>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
require_once 'includes/footer.html';
?>