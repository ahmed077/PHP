<?php
/*
 *
 *
 */
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $title='Items';
    require_once 'includes/init.php';
    $action=isset($_GET['action'])?$_GET['action']:'manage';
    if ($action == 'manage') {
        addHeading();
        $show = $con->prepare("SELECT items.*, categories.name AS Category , members.name AS Member FROM items 
                                INNER JOIN categories ON categories.id=items.cat_id 
                                INNER JOIN members ON members.id=items.member_id");
        $show->execute();
        $items = $show->fetchAll(PDO::FETCH_ASSOC);
        if($show->rowCount() > 0) { ?>
        <div class="container table-responsive">
            <table class="table table-bordered text-center items-table " style="margin:auto">
                <tbody>
                <?php
                echo '<tr>';
                foreach ($items[0] as $key => $val) {
                    if ($key == 'cat_id' || $key == 'member_id') {continue;}
                    echo '<th class="text-center bg-danger">' . ucfirst($key) . '</th>';
                }
                echo '<th class="text-center bg-danger">Controls</th></tr>';
                foreach ($items as $item) {
                    echo '<tr>';
                    foreach ($item as $key2 => $val) {
                        if ($key2 == 'cat_id' || $key2 == 'member_id') {continue;}
                        echo '<td>' . ucfirst($val) . '</td>';
                    }
                    echo '<td>';
                    if($item['approved'] == 0) {
                        echo '<div class="btn btn-info"><a href="items.php?action=approve&itemid=' . $item['id'] . '"><i class="fa fa-check"></i>Approve</a></div>';
                    }
                    echo '<div class="btn btn-success"><a href="items.php?action=edit&itemid=' . $item['id'] . '"><i class="fa fa-edit"></i>Edit</a></div>
                                <div class="btn btn-danger"><a class="confirm" href="items.php?action=delete&itemid=' . $item['id'] . '"><i class="fa fa-close"></i>Delete</a></div>
                              </td></tr>';
                } ?>
                    <tr><td colspan="100%"><div class="btn btn-primary"><a href="items.php?action=add"><i class="fa fa-plus"></i>New Items</a></div></td></tr>
                </tbody>
            </table>
        </div>
    <?php } else { echo "<div class='container'><div class=\"btn btn-primary\"><a href=\"items.php?action=add\"><i class=\"fa fa-plus\"></i>New" . $title . "</a></div></div>" ;}
        } elseif($action == 'add') {
        addHeading(); ?>
        <div class="container">
            <form
                class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 categories-form"
                method="post" action="?action=insert">
                <!--                    name field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name: </label>
                    <div class="col-sm-10">
                        <input type="text" name="name"
                               class="form-control input-lg" autocomplete='off' placeholder="Item Name" required="required">
                    </div>
                </div>
                <!--                    description field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description: </label>
                    <div class="col-sm-10">
                        <input type="text" name="description" placeholder="Item Description" class="form-control input-lg">
                    </div>
                </div>
                <!--                    price field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Price: </label>
                    <div class="col-sm-10">
                        <input type="text" name="price" placeholder="Item Price" class="form-control input-lg" required="required">
                    </div>
                </div>
                <!--                    Country field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Country: </label>
                    <div class="col-sm-10">
                        <input type="text" name="country" placeholder="Item Country" class="form-control input-lg">
                    </div>
                </div>
                <!--                    Status field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status: </label>
                    <div class="col-sm-10">
                        <select name="status">
                            <option value="">...</option>
                            <option value="new">New</option>
                            <option value="like new">Like New</option>
                            <option value="used">Used</option>
                            <option value="old">Old</option>
                        </select>
                    </div>
                </div>
                <?php $stmt3 = $con->prepare("SELECT * FROM members");
                    $stmt3->execute();
                    $users = $stmt3->fetchAll(PDO::FETCH_ASSOC); ?>
                <!--                    Members field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">User: </label>
                    <div class="col-sm-10">
                        <select name="userid">
                            <option value="">...</option>
                            <?php foreach($users as $user) {
                                echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <?php $stmt4 = $con->prepare("SELECT * FROM categories");
                $stmt4->execute();
                $categories = $stmt4->fetchAll(PDO::FETCH_ASSOC); ?>
                <!--                    Categories field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Category: </label>
                    <div class="col-sm-10">
                        <select name="categoryid">
                            <option value="">...</option>
                            <?php foreach($categories as $category) {
                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <!--                    submit button-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-danger center-block">
                    </div>
                </div>
            </form>
        </div>
    <?php } elseif($action == 'insert') {
        addHeading();
        //add member in database
        $nofooter ='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $status = $_POST['status'];
            $description = empty($_POST['description'])?'No Description':$_POST['description'];
            $price = $_POST['price'];
            $country = empty($_POST['country'])?'No Country Added':$_POST['country'];
            $errors = array();
            $userid = $_POST['userid'];
            $categoryid = $_POST['categoryid'];
            if (empty($name) ) {$errors[] = "Name Error";}
            if (empty($price)) {$errors[] = "Price Error";}
            if (empty($status)) {$errors[] = "Status Error";}
            if (empty($userid)) {$errors[] = "User Error";}
            if (empty($categoryid)) {$errors[] = "Category Error";}
            ?>
            <?php
            if (!empty($errors)) {
                echo '<div class="container">';
                foreach ($errors as $err) {
                    echo '<div class=\'alert alert-danger\'>' . $err . '</div>';
                }
                echo '</div>';
            }  else {
                $insert = $con->prepare("INSERT INTO `items` (`name`, `description`, `price`, `add_date`, `country_made`, `status`, `cat_id`, `member_id`) VALUES (?,?,?, now(), ?,?,?,?)");
                $insert->execute(array($name, $description, $price, $country, $status,$categoryid,$userid));
                redirect($insert->rowCount() . " Item Successfully Added", 'success', 'items.php');
            }
        } else {
            redirect('You Can\'t Access This Page', 'danger');
        }
    } elseif($action == 'edit') {
        addHeading();
        if(isset($_GET['itemid']) && is_numeric($_GET['itemid']) && checkDataBase('id','items',$_GET['itemid'])) {
            $itemid = $_GET['itemid'];
            $stmt = $con->prepare('SELECT * FROM items WHERE id = ? LIMIT 1');
            $stmt->execute(array($itemid));
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="container">
            <form
                class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 items-form"
                method="post" action="?action=update">
                <!--                    name field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name: </label>
                    <div class="col-sm-10">
                        <input type="text" name="name"
                               class="form-control input-lg" autocomplete='off' placeholder="Item Name" required="required" value="<?php echo $item['name']; ?>">
                    </div>
                </div>
                <input type="hidden" name='itemid' value="<?php echo $itemid; ?>">
                <!--                    description field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description: </label>
                    <div class="col-sm-10">
                        <input type="text" name="description" placeholder="Item Description" class="form-control input-lg" value="<?php echo $item['description']; ?>">
                    </div>
                </div>
                <!--                    price field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Price: </label>
                    <div class="col-sm-10">
                        <input type="text" name="price" placeholder="Item Price" class="form-control input-lg" required="required" value="<?php echo $item['price']; ?>">
                    </div>
                </div>
                <!--                    Country field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Country: </label>
                    <div class="col-sm-10">
                        <input type="text" name="country" placeholder="Item Country" class="form-control input-lg" value="<?php echo $item['country_made']; ?>">
                    </div>
                </div>
                <!--                    Status field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status: </label>
                    <div class="col-sm-10">
                        <select name="status">
                            <option value="">...</option>
                            <option value="new" <?php if($item['status']=='new'){echo 'selected';} ?>>New</option>
                            <option value="like new" <?php if($item['status']=='like new'){echo 'selected';} ?>>Like New</option>
                            <option value="used" <?php if($item['status']=='used'){echo 'selected';} ?>>Used</option>
                            <option value="old" <?php if($item['status']=='old'){echo 'selected';} ?>>Old</option>
                        </select>
                    </div>
                </div>
                <?php $stmt3 = $con->prepare("SELECT * FROM members");
                $stmt3->execute();
                $users = $stmt3->fetchAll(PDO::FETCH_ASSOC); ?>
                <!--                    Members field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">User: </label>
                    <div class="col-sm-10">
                        <select name="userid">
                            <option value="">...</option>
                            <?php foreach($users as $user) {
                                $v = ($item['member_id']==$user['id'])?$user['id'] . '" selected':$user['id'] . '"';
                            echo '<option value="' . $v . '>' . $user['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <?php $stmt4 = $con->prepare("SELECT * FROM categories");
                $stmt4->execute();
                $categories = $stmt4->fetchAll(PDO::FETCH_ASSOC); ?>
                <!--                    Categories field-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Category: </label>
                    <div class="col-sm-10">
                        <select name="categoryid">
                            <option value="">...</option>
                            <?php foreach($categories as $category) {
                                $v = ($item['cat_id']==$category['id'])?$category['id'] . '" selected':$category['id'] . '"';
                                echo '<option value="' . $v . '>' . $category['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <!--                    submit button-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Save Changes" class="btn btn-danger center-block">
                    </div>
                </div>
            </form>
            <?php $show = $con->prepare("SELECT comments.*, members.name AS Member FROM comments INNER JOIN members ON members.id=comments.member_id WHERE item_id =?");
            $show->execute(array($itemid));
            $comments = $show->fetchAll(PDO::FETCH_ASSOC);
            if($show->rowCount() > 0) { ?>
            <div class="">
                <table class="table table-bordered text-center comments-table " style="margin:auto">
                    <tbody>
                    <?php
                    echo '<tr>';
                    foreach ($comments[0] as $key => $val) {
                        if ($key == 'item_id' || $key == 'member_id' || $key == 'id') {continue;}
                        echo '<th class="text-center bg-danger">' . ucfirst($key) . '</th>';
                    }
                    echo '<th class="text-center bg-danger">Controls</th></tr>';
                    foreach ($comments as $comment) {
                        echo '<tr>';
                        foreach ($comment as $key2 => $val) {
                            if ($key2 == 'item_id' || $key2 == 'member_id' || $key2 == 'id') {continue;}
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
        <?php } ?>
        </div>
    <?php } else {redirect('Item Not Found', 'danger');}
        } elseif ($action == 'update') {
        addHeading();
        $nofooter ='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $status = $_POST['status'];
            $itemid = $_POST['itemid'];
            $description = empty($_POST['description'])?'No Description':$_POST['description'];
            $price = $_POST['price'];
            $country = empty($_POST['country'])?'No Country Added':$_POST['country'];
            $errors = array();
            $userid = $_POST['userid'];
            $categoryid = $_POST['categoryid'];
            if (empty($name) ) {$errors[] = "Name Error";}
            if (empty($price)) {$errors[] = "Price Error";}
            if (empty($status)) {$errors[] = "Status Error";}
            if (empty($userid)) {$errors[] = "User Error";}
            if (empty($categoryid)) {$errors[] = "Category Error";}
            ?>
            <?php
            if (!empty($errors)) {
                echo '<div class="container">';
                foreach ($errors as $err) {
                    echo '<div class=\'alert alert-danger\'>' . $err . '</div>';
                }
                echo '</div>';
            }  else {
                $insert = $con->prepare("UPDATE `items` SET `name` = ?, `description` = ?, `price` = ?, `country_made` = ?, `status` = ?, `cat_id` = ?, `member_id` = ? WHERE id = ?");
                $insert->execute(array($name, $description, $price, $country, $status,$categoryid,$userid, $itemid));
                redirect($insert->rowCount() . " Item Successfully Updated", 'success');
            }
        } else {
            redirect('You Can\'t Access This Page', 'danger');
        }
    } elseif ($action == 'delete') {
        addHeading();
        if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
            $id = $_GET['itemid'];
            $nofooter = '';
            if (checkDataBase('id', 'items', $id)) {
                $stmt = $con->prepare("DELETE FROM `items` WHERE id = ?");
                $stmt->execute(array($id));
                redirect($stmt->rowCount() . ' Records Deleted', 'success', $_SERVER['HTTP_REFERER']);
            } else {redirect('Page Not Found', 'danger');}
        } else {redirect('Item Not Found', 'danger');}
    } elseif ($action == 'approve') {
        addHeading();
        if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
            $id = $_GET['itemid'];
            $nofooter = '';
            if (checkDataBase('id', 'items', $id)) {
                $stmt = $con->prepare("UPDATE items SET approved=1 WHERE id = ?");
                $stmt->execute(array($id));
                redirect($stmt->rowCount() . ' Records Approved', 'success', $_SERVER['HTTP_REFERER']);
            } else {redirect('Item Not Found', 'danger');}
        } else {redirect('Item Not Found', 'danger');}
    } else {
        redirect('Item Not Found', 'danger');
    }
    require_once 'includes/footer.html';
} else {
    redirect("You Will Be Redirected in 3 Seconds", "info");
}