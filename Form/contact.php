<?php
$title = "Contact Us";
if (isset($_COOKIE['logged'])) {
    session_start();
}
?>
<?php
    require_once 'base/basic.html';
    require_once "base/header.html";
?>
    <h1 class="text-xs-center text-danger">Contact Form</h1>
    <form action="" class="col-md-4 col-sm-8 col-xs-12 offset-md-4 offset-sm-2 col-xs-offset-0">
        <div class="form-group">
            <label for="">Name: </label>
            <input type="text" class="input-lg form-control"
            <?php
            if (isset($_SESSION['name'])) {
                echo "value=" . $_SESSION['name'] . " disabled";
            }?>
            >
        </div>
        <div class="form-group">
            <label for="">Email: </label>
            <input type="text" class="input-lg form-control"
                <?php
            if (isset($_SESSION['name'])) {
                $file_dir = __DIR__ . '\db\\' . $_SESSION['name'] . '.txt';
                $file = fopen($file_dir, 'r');
                $content = fread($file, filesize($file_dir));
                $start = stripos($content, 'email: ') + 7;
                $end = stripos($content, '.', $start) - $start;
                $email = substr($content, $start, $end);
                echo "value=" . $email . " disabled";
            }?>>
        </div>
        <div class="form-group">
            <label for="">About: </label>
            <select class="input-lg form-control">
                <option value="">TEST1</option>
                <option value="">TEST2</option>
                <option value="">TEST3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Message: </label>
            <textarea class="input-lg form-control" rows="5"></textarea>
        </div>
        <input type="submit" class="center-block input-lg form-control" value="Submit">
    </form>
    <?php require_once "base/footer.html";?>
</body>
</html>