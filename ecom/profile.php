<?php
session_start();
$title = "Profile";
require_once 'includes/init.php';
if(isset($_SESSION['user'])) { ?>
<h1 class="text-danger text-center">My Profile</h1>
<div class="information">
    <div class="container">
        <div class="panel panel-primary mgtop">
            <div class="panel-heading">
                My Informataion
                <div class="btn btn-success btn-xs pull-right edit"><i class="fa fa-edit"></i>Edit</div>
            </div>
            <div class="panel-body">
                <?php
                    $info = getUserInfo('members','WHERE id=' . $_SESSION['id']);
                    foreach ($info as $key => $value) {
                        if($key=='id' || $key=='password'){continue;}
                        echo '<div class="col-sm-4" data-value="' . $key . '">' . $key . '</div>';
                        echo '<div class="col-sm-8">' . $value . '</div>';
                    }
                ?>
            </div>
        </div>
        <div class="panel panel-primary mgtop">
            <div class="panel-heading">
                My Ads
                <div class="btn btn-default btn-xs pull-right Add"><i class="fa fa-plus"></i><a href="newad.php?action=add">New Ad</a></div>
            </div>
            <div class="panel-body">
                <?php
                $items = getRecords('*','items','DESC', 'WHERE member_id=' . $_SESSION['id']);
                if($items!=0){
                    putItems();
                } else {echo 'No Ads';}
                //putItems();
                ?>
            </div>
        </div>
        <div class="panel panel-primary mgtop">
            <div class="panel-heading">
                My Comments
            </div>
            <div class="panel-body">
                <?php
                $comments = getRecords('*','comments','DESC', 'WHERE member_id=' . $_SESSION['id']);
                if($comments!=0){
                    putComments();
                } else {echo 'No Comments';}
                //putItems();
                ?>
            </div>
        </div>
    </div>
</div>
<?php } else {header('Location:login.php');}
require_once 'includes/footer.html';
?>