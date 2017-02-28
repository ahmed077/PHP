<?php
session_start();
    if(isset($_SESSION['userdata']['username'])) {
        $title = 'Dashboard';
        require_once 'includes/init.php';
        ?>
        <h1 class="text-center text-danger">Welcome to Dashboard</h1>
        <div class="container text-center stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat">
                        <p class="lead">Members</p>
                        <a href="members.php?action=manage">
                            <span class="center-block"><?php echo countItems('id','members', 'WHERE groupID != 1');?></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        <p class="lead">Pending Approval</p>
                        <a href="members.php?action=manage&page=pending">
                            <span class="center-block"><?php echo countItems('id','members', 'WHERE regStatus=0');?></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        <p class="lead">Items</p>
                        <a href="items.php?action=manage">
                            <span class="center-block"><?php echo countItems('id','items');?></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat">
                        <p class="lead">Comments</p>
                        <a href="comments.php">
                            <span class="center-block"><?php echo countItems('id','comments');?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container text-center panels">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php $n = 5;?>
                            <i class="fa fa-users"></i>Latest <?php echo $n;?> Registered Users
                        </div>
                        <div class="panel-body">
                            <div class="row title">
                                <div class="col-xs-4 panel-title">Name</div>
                                <div class="col-xs-4 panel-title">Registeration Date</div>
                            </div>
                            <?php $users = getLatest('*', 'members', 'regDate', $n, "WHERE groupID != 1");
                            foreach ($users as $user) {
                                echo '<div class="row user">
                                        <div class="col-xs-3">' . $user['name'] . '</div>';
                                  echo '<div class="col-xs-4">' . $user['regDate'] . '</div>';
                                if ($user['regStatus'] == 0) {
                                    echo '<a class="col-xs-3" href="members.php?action=approve&userid=' . $user['id'] . '"><div class="btn btn-info"><i class="fa fa-check"></i>Approve</div></a>';
                                    echo '<a class="col-xs-2" href="members.php?action=edit&userid=' . $user['id'] . '"><div class="btn btn-success"><i class="fa fa-edit"></i>Edit</div></a>
                                      </div>';
                                } else {
                                    echo '<a class="col-xs-2 col-xs-offset-3" href="members.php?action=edit&userid=' . $user['id'] . '"><div class="btn btn-success"><i class="fa fa-edit"></i>Edit</div></a>
                                      </div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tags"></i>Latest <?php echo $n;?> Added Items
                        </div>
                        <div class="panel-body">
                            <div class="row title">
                                <div class="col-xs-4 panel-title">Item Name</div>
                                <div class="col-xs-4 panel-title">Adding Date</div>
                            </div>
                            <?php $items = getLatest('*', 'items', 'add_date', $n);
                            foreach ($items as $item) {
                                echo '<div class="row item">
                                        <div class="col-xs-3">' . $item['name'] . '</div>';
                                echo '<div class="col-xs-4">' . $item['add_date'] . '</div>';
                                echo '<a class="col-xs-2 col-xs-offset-3" href="items.php?action=edit&itemid=' . $item['id'] . '"><div class="btn btn-success"><i class="fa fa-edit"></i>Edit</div></a>
                                    </div>';
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once 'includes/footer.html';
    } else {
        header("Location: index.php");
        exit();
    }