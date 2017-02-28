<?php
session_start();
$title='New Add';
if(isset($_SESSION['user'])){
    require_once 'includes/init.php';
    $action = isset($_GET['action'])?$_GET['action']:'add';
    if ($action == 'add') {?>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Ad</div>
            <div class="panael-body create-ad-panel">
                <div class="row">
                    <div class="col-sm-8">
                        <form
                            class="form-horizontal" method="post" action="?action=insert">
                            <!--                    name field-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="name"
                                           data-target="h3" class="form-control input-lg" autocomplete='off' placeholder="Item Name" required="required">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                            <!--                    description field-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description: </label>
                                <div class="col-sm-10">
                                    <input data-target="p" type="text" name="description" placeholder="Item Description" class="form-control input-lg">
                                </div>
                            </div>
                            <!--                    price field-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price: </label>
                                <div class="col-sm-10">
                                    <input data-target=".price-tag" type="text" name="price" placeholder="Item Price" class="form-control input-lg" required="required">
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
                    <div class="col-sm-4">
                        <div class="thumbnail live-view">
                            <span class="price-tag" data-text="Price">Price</span>
                            <img src="http://placehold.it/400" />
                            <div class="caption">
                                <h3 data-text="Item Name">Item Name</h3>
                                <p data-text="Item Description">Item Description</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } elseif($action=='insert'){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'],FILTER_SANITIZE_STRING);
            $country = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $member_id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $errors = array();
            if(empty($description)){$description='No Description';}
            if(empty($country)){$description='No Country Added';}
            if(!isset($_POST['categoryid'])){$error[] = 'You Must Choose A Category';} else{$cat_id = filter_var($_POST['categoryid'],FILTER_SANITIZE_NUMBER_INT);}
            if(!isset($_POST['status'])){$error[] = 'You Must Choose A Status';} else{$status = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);}
            if(!empty($errors)){
                $stmt = $con->prepare('INSERT INTO items(`name`,`description`,`price`,`add_date`,`country_made`,`status`,`cat_id`,`member_id`) VALUES (?,?,?,now(),?,?,?,?)');
                $stmt->execute(array($name,$description,$price,$country,$status,$cat_id,$member_id));
                redirect($stmt->rowCount() . ' Item Added Successfully','success','profile.php');
            } else {
                $msg = 'The Following Errors Occured<pre>';foreach($error as $err){$msg .= '<br/>' . $err;}$msg .= '</pre>';
                redirect($msg, 'danger',$_SERVER['HTTP_REFERER'],7);}
        } else {redirect('You Are Not Allowed To Be Here');}
    }else {redirect('Page Not Found');}
require_once 'includes/footer.html';
} else {redirect('You Are Not Allowed To Be Here');}
?>