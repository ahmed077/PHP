<?php
session_start();
if (isset($_SESSION['userdata']['username'])) {
    $title = 'Categories';
    $action = isset($_GET['action']) ? $_GET['action'] : 'manage';
    require_once 'includes/init.php';
    if ($action == 'add') {addHeading()?>
<!--        Add New Category-->
            <div class="container">
                <form
                    class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 categories-form"
                    method="post" action="?action=insert">
                    <!--                    name field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name: </label>
                        <div class="col-sm-10">
                            <input type="text" name="name"
                                   class="form-control input-lg" autocomplete='off' placeholder="Category Name" required="required">
                        </div>
                    </div>
                    <!--                    description field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description: </label>
                        <div class="col-sm-10">
                            <input type="text" name="description" placeholder="Category Description" class="form-control input-lg">
                        </div>
                    </div>
                    <!--                    ordering field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Order: </label>
                        <div class="col-sm-10">
                            <input type="text" name="ordering" placeholder="Category Order" class="form-control input-lg">
                        </div>
                    </div>
                    <!--                    visibility field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Visibility: </label>
                        <div class="col-sm-10">
                            <!--                            version 1-->
                            <!--<div class="col-xs-6">
                                <input id='vis-yes' type="radio" name="visibility" value="1" checked>
                                <label class="control-label" for="vis-yes">Yes</label>
                            </div>
                            <div class="col-xs-6">
                                <input id='vis-no' type="radio" name="visibility" value="0">
                                <label class="control-label" for="vis-no">No</label>
                            </div>-->
                            <!--                            version 2-->
                            <i class="fa fa-toggle-on pull-right text-success"></i>
                            <input type="hidden" name="visibility" value="1">
                        </div>
                    </div>
                    <!--                    comments field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comments: </label>
                        <div class="col-sm-10">
                            <!--                            version 1-->
                            <!--<div class="col-xs-6">
                                <input id='com-yes' type="radio" name="comments" value="1" checked>
                                <label class="control-label" for="com-yes">Yes</label>
                            </div>
                            <div class="col-xs-6">
                                <input id='com-no' type="radio" name="comments" value="0">
                                <label class="control-label" for="com-no">No</label>
                            </div>-->
                            <!--                            version 2-->
                            <i class="fa fa-toggle-on pull-right text-success"></i>
                            <input type="hidden" name="comments" value="1">
                        </div>
                    </div>
                    <!--                    ads field-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ads: </label>
                        <div class="col-sm-10">
                            <!--                            version 1-->
                            <!--<div class="col-xs-6">
                                <input id='ad-yes' type="radio" name="ads" value="1" checked>
                                <label class="control-label" for="ad-yes">Yes</label>
                            </div>
                            <div class="col-xs-6">
                                <input id='ad-no' type="radio" name="ads" value="0">
                                <label class="control-label" for="ad-no">No</label>
                            </div>-->
                            <!--                            version 2-->
                            <i class="fa fa-toggle-on pull-right text-success"></i>
                            <input type="hidden" name="ads" value="1">
                        </div>
                    </div>
                    <!--                    submit button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-danger center-block">
                        </div>
                    </div>
                </form>
            </div>
        <?php
    } elseif ($action == 'insert') {
//        Insert New Category in Database
        $nofooter='';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            if(empty($name)) {
                redirect("Category Name Can't be Empty", "danger", $_SERVER['HTTP_REFERER']);
            } elseif(checkDataBase('name', 'categories', $name)) {
                redirect("Category Name Already Exists", "danger", $_SERVER['HTTP_REFERER']);
            } else {
                $description = $_POST['description'];
                $ordering = $_POST['ordering'];
                $comments = $_POST['comments'];
                $ads = $_POST['ads'];
                $visibility = $_POST['visibility'];
                $stmt = $con->prepare('INSERT INTO `categories`(name, description, ordering, visibility, allow_comment, allow_ads) VALUES(:zname,:zdescription,:zordering,:zvisibility,:zcomments,:zads)');
                $stmt->execute(array(
                    'zname' => $name,
                    'zdescription' => $description,
                    'zordering' => $ordering,
                    'zvisibility' => $comments,
                    'zcomments' => $visibility,
                    'zads' => $ads
                ));
                echo '<h1 class="text-center text-danger">Insert <?php echo $title;?></h1>';
                redirect($stmt->rowCount() . ' Category Added', 'success', 'categories.php');
            }?>
    <?php } else {redirect("You Are Not Allowed To Be Here", 'danger');}
    } elseif ($action == 'update') {
        $nofooter='';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $oldname = $_POST['oldname'];
            if (empty($name)) {
                redirect("Category Name Can't be Empty", "danger", $_SERVER['HTTP_REFERER']);
            } elseif (checkDataBase('name', 'categories', $name) && $name != $oldname) {
                redirect("Category Name Already Exists", "danger", $_SERVER['HTTP_REFERER']);
            } else {
                $id=$_POST['id'];
                $description = $_POST['description'];
                $ordering = $_POST['ordering'];
                $comments = $_POST['comments'];
                $ads = $_POST['ads'];
                $visibility = $_POST['visibility'];
                $stmt = $con->prepare("UPDATE categories SET name=?, description=?, ordering=?, allow_comment=?, allow_ads=?, visibility=? WHERE id=? ");
                $stmt->execute(array($name, $description, $ordering, $comments, $ads, $visibility, $id));
                ?>
                <h1 class="text-center text-danger">Update <?php echo $title;?></h1>

            <?php redirect($stmt->rowCount() . ' Category Updated', 'success', '?action=manage');
            }
        } else {redirect("There's No Such ID");}
    } elseif ($action == 'edit') {
        $id=$_GET['id'];
        $stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute(array($id));
        $cat_data = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
    <h1 class="text-center text-danger">Edit <?php echo $title;?></h1>
    <div class="container">
        <form
            class="form-horizontal col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 categories-form"
            method="post" action="?action=update">
            <!--                    name field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Name: </label>
                <div class="col-sm-10">
                    <input type="text" name="name"
                           class="form-control input-lg" autocomplete='off' placeholder="Category Name" value="<?php echo $cat_data['name'];?>" required="required">
                </div>
            </div>
            <!--                    description field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Description: </label>
                <div class="col-sm-10">
                    <input type="text" name="description" placeholder="Category Description" class="form-control input-lg" value="<?php echo $cat_data['description'];?>">
                </div>
            </div>
            <!--                    ordering field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Order: </label>
                <div class="col-sm-10">
                    <input type="text" name="ordering" placeholder="Category Order" class="form-control input-lg" value="<?php echo $cat_data['ordering'];?>">
                </div>
            </div>
            <!--                    visibility field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Visibility: </label>
                <div class="col-sm-10">
                    <!--                            version 2-->
                    <?php echo checkStatus($cat_data['visibility']);?>
                    <input type="hidden" name="visibility" value="<?php echo $cat_data['visibility'];?>">
                </div>
            </div>
            <!--                    comments field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Comments: </label>
                <div class="col-sm-10">
                    <!--                            version 2-->
                    <?php echo checkStatus($cat_data['allow_comment']);?>
                    <input type="hidden" name="comments" value="<?php echo $cat_data['allow_comment'];?>">
                </div>
            </div>
            <!--                    ads field-->
            <div class="form-group">
                <label class="col-sm-2 control-label">Ads: </label>
                <div class="col-sm-10">
                    <!--                            version 2-->
                    <?php echo checkStatus($cat_data['allow_ads']);?>
                    <input type="hidden" name="ads" value="<?php echo $cat_data['allow_ads'];?>">
                </div>
            </div>
            <!--                send id-->
            <input type="hidden" value="<?php echo $cat_data['id'];?>" name="id">
            <input type="hidden" value="<?php echo $cat_data['name'];?>" name="oldname">
            <!--                    submit button-->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Save Changes" class="btn btn-danger center-block">
                </div>
            </div>
        </form>
    </div>
<?php } elseif ($action == 'delete') {
    $nofooter='';
        if(isset($_GET['id'])&&checkDataBase('id','categories',$_GET['id'])) {
            $stmt = $con->prepare('DELETE FROM categories WHERE id=?');
            $stmt->execute(array($_GET['id']));
        ?>
        <h1 class="text-center text-danger">Delete <?php echo $title;?></h1>

    <?php redirect($stmt->rowCount() . ' Category Deleted', 'success', $_SERVER['HTTP_REFERER']);
        } else {redirect("There's No Such ID");}
    } elseif ($action == 'manage') {
    $sort_array = array("ASC", "DESC");
    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
        $sort = 'ORDER BY `ordering` ' . $_GET['sort'];
    } else {$sort = '';}
    $stmt = $con->prepare("SELECT * FROM categories $sort");
    $stmt->execute();
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        addHeading();
    ?>
<!--    <h1 class="text-center text-danger">All --><?php //echo $title;?><!--</h1>-->
    <div class="container categories">
        <div class="panel panel-primary">
            <div class="panel-heading">Manage Categories</div>
            <div class="btn btn-info new-cat"><a href="?action=add"><i class="fa fa-plus"></i>Add New Category</a></div>
            <div class="sort"><span>Sort</span>
                <a href="?action=manage&sort=ASC" class="<?php if(isset($_GET['sort']) && $_GET['sort']=='ASC'){echo 'active';}?>"><i class="fa fa-sort-asc fa-2x"></i></a>
                <a href="?action=manage&sort=DESC" class="<?php if(isset($_GET['sort']) && $_GET['sort']=='DESC'){echo 'active';}?>"><i class="fa fa-sort-desc fa-2x"></i></a>
            </div>
            <div class="change-view">
                <ul class="list-inline list-unstyled">
                    <li>Classic</li>
                    <li class="active">Full</li>
                </ul>
            </div>
            <div class="panel-body"><?php
            foreach($cats as $cat) {
            echo '<div class="cat">
                      <div class="hidden-buttons">
                          <a href="?action=edit&id=' . $cat['id'] . '">
                              <div class="btn btn-success"><i class="fa fa-edit"></i>Edit</div>
                          </a>
                          <a href="?action=delete&id=' . $cat['id'] . '" class="confirm">
                              <div class="btn btn-danger"><i class="fa fa-close"></i>Delete</div>
                          </a>
                      </div>
                      <h2>' . $cat['name'] . '</h2>
                      <div class="cat-info">
                          <p>Description: ' . $cat['description'] . '</p>
                          <span class="center-block">Ordering: ' . $cat['ordering'] . '</span>
                          <span class="center-block">Visibility: ' . checkStatus($cat['visibility']) . '</span>
                          <span class="center-block">Comments: ' . checkStatus($cat['allow_comment']) . '</span>
                          <span class="center-block">Ads: ' . checkStatus($cat['allow_ads']) . '</span>
                      </div>
                  </div>';
            }
          ?></div>
        </div>
    </div>
<?php } else {redirect("Page Not Found", "danger");}
    require_once 'includes/footer.html';
} else {
    header('Location: index.php');
}