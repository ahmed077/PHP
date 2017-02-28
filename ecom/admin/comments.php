<?php
/*
 *
 *
 */
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $title='Comments';
    require_once 'includes/init.php';
    $action=isset($_GET['action'])?$_GET['action']:'manage';
    if ($action == 'manage') {
        addHeading();
        $show = $con->prepare("SELECT comments.*, items.name AS Item , members.name AS Member FROM comments 
                                INNER JOIN items ON items.id=comments.item_id 
                                INNER JOIN members ON members.id=comments.member_id");
        $show->execute();
        $comments = $show->fetchAll(PDO::FETCH_ASSOC);
        if($show->rowCount() > 0) { ?>
            <div class="container table-responsive">
                <table class="table table-bordered text-center comments-table " style="margin:auto">
                    <tbody>
                    <?php
                    echo '<tr>';
                    foreach ($comments[0] as $key => $val) {
                        if ($key == 'item_id' || $key == 'member_id') {continue;}
                        echo '<th class="text-center bg-danger">' . ucfirst($key) . '</th>';
                    }
                    echo '<th class="text-center bg-danger">Controls</th></tr>';
                    foreach ($comments as $comment) {
                        echo '<tr>';
                        foreach ($comment as $key2 => $val) {
                            if ($key2 == 'item_id' || $key2 == 'member_id') {continue;}
                            echo '<td>' . ucfirst($val) . '</td>';
                        }
                        echo '<td>';
                        if($comment['approved'] == 0) {
                            echo '<div class="btn btn-info"><a href="comments.php?action=approve&commentid=' . $comment['id'] . '"><i class="fa fa-check"></i>Approve</a></div>';
                        }
                        echo '<div class="btn btn-success"><a href="comments.php?action=edit&commentid=' . $comment['id'] . '"><i class="fa fa-edit"></i>Edit</a></div>
                            <div class="btn btn-danger"><a class="confirm" href="comments.php?action=delete&commentid=' . $comment['id'] . '"><i class="fa fa-close"></i>Delete</a></div>
                              </td></tr>';
                    } ?>
                    </tbody>
                </table>
            </div>
        <?php }
    } elseif ($action == 'edit') {
        addHeading();
        if(isset($_GET['commentid']) && is_numeric($_GET['commentid']) && checkDataBase('id','comments',$_GET['commentid'])) {
            $commentid = $_GET['commentid'];
            $stmt = $con->prepare('SELECT comment FROM comments WHERE id = ? LIMIT 1');
            $stmt->execute(array($commentid));
            $comment = $stmt->fetch(PDO::FETCH_ASSOC); ?>
            <div class="container">
                <form
                    class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 comment-form"
                    method="post" action="?action=update">
                    <input type="hidden" name='commentid' value="<?php echo $commentid; ?>">
                    <!--                    description field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comment: </label>
                        <div class="col-sm-10">
                            <textarea name="comment" class="form-control" required="required" rows="10"><?php echo $comment['comment']; ?></textarea>
                        </div>
                    </div>
                    <!--                    submit button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Changes" class="btn btn-danger center-block">
                        </div>
                    </div>
                </form>
            </div>
    <?php }
    } elseif ($action == 'update') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['commentid'];
            if(checkDataBase('id','comments',$id)) {
                $comment=$_POST['comment'];
                $stmt = $con->prepare('UPDATE comments SET comment=? WHERE id=?');
                $stmt->execute(array($comment,$id));
                redirect($stmt->rowCount() . ' Comment Edited', 'success', 'comments.php');
            } else {redirect('Comment not found');}
        } else {redirect('You Can\'t Access This Page');}
    } elseif ($action == 'delete') {
        addHeading();
        if(isset($_GET['commentid']) && is_numeric($_GET['commentid'])) {
            $id = $_GET['commentid'];
            $nofooter = '';
                if (checkDataBase('id', 'comments', $id)) {
                    $stmt = $con->prepare('DELETE FROM comments WHERE id = ?');
                    $stmt->execute(array($id));
                    redirect($stmt->rowCount() . ' Comment Deleted', 'success', $_SERVER['HTTP_REFERER']);
                } else {redirect('Comment Not Found', 'danger');}
            } else {redirect('Comment Not Found', 'danger');}
    } elseif ($action == 'approve') {
        addHeading();
        if(isset($_GET['commentid']) && is_numeric($_GET['commentid'])) {
            $id = $_GET['commentid'];
            $nofooter = '';
            if (checkDataBase('id', 'comments', $id)) {
                $stmt = $con->prepare("UPDATE comments SET approved=1 WHERE id = ?");
                $stmt->execute(array($id));
                redirect($stmt->rowCount() . ' Records Approved', 'success', $_SERVER['HTTP_REFERER']);
            } else {redirect('Comment Not Found', 'danger');}
        } else {redirect('Comment Not Found', 'danger');}
    }else {
        redirect('Page Not Found', 'danger');
    }
    require_once 'includes/footer.html';
} else {
    redirect("You Will Be Redirected in 3 Seconds", "info");
}