<?php
ob_start();
session_start();
$pageTitle = 'Show Items';

include 'init.php';

// Check If Get Request items Is Numeric & Get The Integer Value Of It

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

// Select All Data Depend On This ID

$stmt = $con->prepare(" SELECT 
                            items.*, categories.Name AS category_name , users.Username
                            FROM items 
                            INNER JOIN categories ON categories.ID = items.Cat_ID 
                            INNER JOIN users ON users.UserID = items.Member_ID
                            WHERE `Item_ID` = ?
                            AND Approve = 1");

// Execute Query

$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if ($count > 0) {
// Fetch Data From Database
$item = $stmt->fetch();

?>
    <h1 class="text-center"><?php echo $item['Name']; ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="img.png" alt=""/>
            </div>
            <div class="col-md-9 item-info">
            <h2><?php echo $item['Name']; ?></h2>
            <p><?php echo $item['Description']; ?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date']; ?>
                </li>
                <li>
                    <i class="fa fa-money fa-fw"></i>
                    <span>Price</span> : $<?php echo $item['Price']; ?>
                </li>
                <li>
                    <i class="fa fa-building fa-fw"></i>
                    <span>Made In</span> : <?php echo $item['Country_Made']; ?>
                </li>
                <!-- category_name coming from the inner join -->
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>"><?php echo $item['category_name']; ?></a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span>Added By</span> : <a href="#"><?php echo $item['Username']; ?></a>
                </li>
                <li class="items-tags">
                    <i class="fa fa-tags fa-fw"></i>
                    <span>tags</span> :
                    <?php 
                        $allTags = explode(',', $item['tags']);
                        foreach ( $allTags as $tag) {
                            $tag = str_replace(" ", "", $tag); //  remove spaces
                            // $tag = strtolower($tag); //  to lower case
                            if (! empty($tag)) {
                            echo "<a href='tags.php?name=". strtolower($tag) ."'>" . $tag . "</a>";
                            }
                        }
                    ?>
                </li>
            </ul>
            </div>
        </div>
        <?php if (isset($_SESSION['user'])) { ?>
        <!-- Start Add Comment -->
        <hr class="custom-hr"/>
        <div class="row">
            <!-- Skip 3 columns -->
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Add Comment</h3>
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                        <textarea name="comment" id="" placeholder="Write Comment" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment" />
                    </form>
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = strip_tags(htmlspecialchars($_POST['comment'], ENT_QUOTES, "UTF-8")); // Comment
                            $userid = $item['Member_ID']; /// User ID
                            $itemid = $_SESSION['uid']; /// Item ID

                            if (! empty($comment)){
                                $stmt = $con->prepare("INSERT INTO 
                                comments(comment, status, comment_date, item_id, user_id) 
                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                                $stmt->execute(array(
                                    'zcomment' => $comment,
                                    'zitemid' => $itemid,
                                    'zuserid' => $userid
                                ));
                                if ($stmt->rowCount() > 0) {
                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">Comment Can\'t Be Empty</div>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- End Add Comment -->
        <?php }else {
            echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
        } ?>
        <!-- Start Comments -->
        <hr class="custom-hr" />
        <?php 
            
            // Select All Comments Except Admin
            $stmt = $con->prepare("SELECT 
            comments.*, users.Username AS Member
            FROM comments
            INNER JOIN users ON
            users.UserID = comments.user_id 
            WHERE item_id = ? AND `status` = 1;
            ");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
            ?>
            <?php foreach ($rows as $row) { ?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt=""/>
                            <?php echo $row['Member']; ?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo $row['comment']; ?></p>
                        </div>
                        <!-- $row['comment_date']; -->
                        <!-- $row['user_id']; -->
                    </div>
                </div>
            <hr class="custom-hr" />
            <?php } ?>
        <!-- End Comments -->
    </div>
<?php
}else {
    echo '<div class="container">';
    echo '<div class="alert alert-danger">There\'s No Such ID Or This Item Waiting For Approval</div>';
    echo '</div>';
}

include $tpl . 'footer.php';
exit();
ob_end_flush();
?>