<?php
/*
 *
 *
 */
session_start();
$title='Items';
require_once 'includes/init.php';
if(isset($_GET['itemid'])){
    $id=$_GET['itemid'];
    $stmt = $con->prepare("SELECT items.*, categories.name AS Category , members.name AS Member FROM items
                                INNER JOIN categories ON categories.id=items.cat_id 
                                INNER JOIN members ON members.id=items.member_id  WHERE items.id=?");
    $stmt->execute(array($id));
    $item=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($item)){ ?>
    <div class="container">
        <h1 class="text-center"><?php echo $item['name']; ?></h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-4 col-xs-6">
                    <div class="thumbnail">
                        <img src="http://placehold.it/400" alt="">
                        <span class="price-tag"><?php echo $item['price']; ?></span>
                    </div>
                </div>
                <div class="col-sm-8 col-xs-6 mgtop">
                    <div class="row">
                        <div class="col-sm-4 col-xs-6">
                            <p>Description:</p>
                            <p>Price:</p>
                            <p>Add Date:</p>
                            <p>Approval Status:</p>
                            <p>Category:</p>
                            <p>Owner:</p>
                        </div>
                        <div class="col-sm-8 col-xs-6">
                            <p><?php if(empty($item['description'])){echo 'No Description';}else{echo$item['description'];} ?></p>
                            <p><?php echo $item['price']; ?></p>
                            <p><?php echo $item['add_date']; ?></p>
                            <p><?php if($item['approved']==1){echo 'Approved';}else{echo'Waiting Approval';} ?></p>
                            <p><?php echo $item['Category']; ?></p>
                            <p><?php echo $item['Member']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php //Get Comments
        $stmt2 = $con->prepare('SELECT comments.*, members.name AS member FROM comments INNER JOIN members ON members.id=comments.member_id WHERE item_id=?');
        $stmt2->execute(array($id));
        $comments = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="panel panel-primary item-comments">
            <div class="panel-heading">Comments</div>
            <div class="panel-body">
                <?php foreach($comments as $comment){ ?>
                    <div class="col-lg-2 col-xs-4">
                        <span class="center-block"><?php echo$comment['member']; ?></span>

                    </div>
                    <div class="col-lg-10 col-xs-8"><?php echo$comment['comment']; ?><span class="center-block text-muted pull-right"><?php echo$comment['date']; ?></span></div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else {echo 'Item Not Found';}
}
require_once 'includes/footer.html';