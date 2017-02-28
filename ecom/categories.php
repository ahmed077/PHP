<?php
session_start();
$title='Categories';
require_once 'includes/init.php';
if(isset($_GET['pageid'])){$pageid=$_GET['pageid'];$pagename=str_replace('_',' ',$_GET['pagename']);
    if($pageid==1){
        addHeadingFE();
        echo '<div class="container">';
        $items = getRecords('*','items','DESC', 'WHERE cat_id=' . $pageid);
        if($items==0) {echo 'No Items in This Category';}
        else{
            putItems();
        }
        echo '</div>';
    } elseif($pageid==2) {
        addHeadingFE();
        echo '<div class="container">';
        $items = getRecords('*','items','DESC', 'WHERE cat_id=' . $pageid);
        if($items==0) {echo 'No Items in This Category';}
        else{
            putItems();
        }
        echo '</div>';
    } elseif($pageid==3) {
        addHeadingFE();
        echo '<div class="container">';
        $items = getRecords('*','items','DESC', 'WHERE cat_id=' . $pageid);
        if($items==0) {echo 'No Items in This Category';}
        else{
            putItems();
        }
        echo '</div>';
    } elseif($pageid==4) {
        addHeadingFE();
        echo '<div class="container">';
        $items = getRecords('*','items','DESC', 'WHERE cat_id=' . $pageid);
        if($items==0) {echo 'No Items in This Category';}
        else{
            putItems();
        }
        echo '</div>';
    } elseif($pageid==5) {
        addHeadingFE();
        echo '<div class="container">';
        $items = getRecords('*','items','DESC', 'WHERE cat_id=' . $pageid);
        if($items==0) {echo 'No Items in This Category';}
        else{
            putItems();
        }
        echo '</div>';
    } else {
        // show all categories
    }
} else {
    // show all categories
}
require_once 'includes/footer.html';