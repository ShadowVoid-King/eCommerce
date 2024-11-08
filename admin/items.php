<?php
/* 

==================================_________====================================
===                                |Items|                                  ===
===                               |=======|                                 ===
==================================|||||||||====================================

*/
ob_start(); // Object Buffering Start * Save Storage On Memory Except Header

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // Check Query

    // If The Page Is Main Page

    if ($do == 'Manage') { //~ Manage Items Page 
        
        /* 
        *Get All items , categories.Name AS category_name, users.Username Data 
        **Which [ Categories.ID = items.Cat_ID ] So It Will return -> Category Name.
        -Exp: items.Cat_ID = 1 , Categories.ID = 1 
                Than Categories.ID = 1 
                    Also Equal Categories.Name = Ps4

        ** And Same For [ Users.UserID = items.Member.ID ] So It WIll return -> Username
        -Exp: items.Member.ID = 1 , Users.UserID = 1 
                Than Users.UserID = 1  
                    Also Equal Users.Username = Osama
        ** In Short When It's Get Match It Will Bring Data He Need Like Name For Category, Username For Member.
        */
        $stmt = $con->prepare(" SELECT
                                    items.*, categories.Name AS category_name, users.Username
                                FROM items
                                INNER JOIN categories ON
                                    categories.ID = items.Cat_ID
                                INNER JOIN users ON
                                    users.UserID = items.Member_ID
                                ORDER BY 
                                    Item_id DESC
        ");
        
        // Execute The Statement
        $stmt->execute();
        
        // Assign To Variable
        $items = $stmt->fetchAll();
        
        if (! empty($items)) {
    ?>

        <h1 class="text-center">Manage Item</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>
                    <!-- LOOP -->
                    <?php
                        foreach ($items as $item) :
                            echo '<tr>';
                            echo '<td>' . $item["Item_ID"]   . '</td>';
                            echo '<td>' . $item["Name"] . '</td>';
                            echo '<td>' . $item["Description"]    . '</td>';
                            echo '<td>' . $item["Price"] . '</td>';
                            echo '<td>' . $item["Add_Date"]     .  '</td>';
                            echo '<td>' . $item["category_name"]     .  '</td>';
                            echo '<td>' . $item["Username"]     .  '</td>';
                            echo '<td>
                                <a href="items.php?do=Edit&itemid=' . $item["Item_ID"] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="items.php?do=Delete&itemid=' . $item["Item_ID"] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
                                if ( $item['Approve'] == 0 ) {
                                    echo '<a href="items.php?do=Approve&itemid=' . 
                                            $item["Item_ID"] . 
                                        '" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                                }
                            echo '</td>';
                            echo '</tr>';
                        endforeach;
                    ?>
                </table>
            </div>
            
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a> <!-- For Extra Space -->
            
        </div>
        <!--//~ End Manage Page -->

    <?php }else {
            echo '<div class="nice-message">There\'s No Items To Show</div>';
            echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';
        }
    
    }elseif ($do == 'Add') { //~ Add Page 
    ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item" />
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item" />
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item" />
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" required="required" placeholder="Country of Made" />
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="members">
                                <option value="0">...</option>
                                <?php 
                                $allMembers = getAllFrom('*', 'users', '', NULL , 'UserID');
                        // $stmt = $con->prepare("SELECT * FROM users ");
                        // $stmt->execute();
                        // $users = $stmt->fetchAll();
                                    foreach ($allMembers as $user) {
                                        echo "<option value='" . $user["UserID"] . "'>" . $user["Username"] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Category Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                        <select name="categories">
                                <option value="0">...</option>
                                <?php 
                                    $allCats = getAllFrom('*', 'categories', 'WHERE parent = 0', NULL , 'ID');
                            // $stmt = $con->prepare("SELECT * FROM categories ");
                            // $stmt->execute();
                            // $cats = $stmt->fetchAll();
                                    foreach ($allCats as $cat) {
                                        echo "<option value='" . $cat["ID"] . "'>" . $cat["Name"] . "</option>";
                                        $childCats = getAllFrom('*', 'categories', "WHERE parent = {$cat["ID"]}", NULL , 'ID');
                                        foreach ( $childCats as $child) {
                                            echo "<option value='" . $child["ID"] . "'>- - " . $child["Name"] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    
<!-- Start Rating Field -->
<!-- <div class="form-group form-group-lg">
    <label class="col-sm-2 control-label">Rating</label>
    <div class="col-sm-10 col-md-6">
        <select class="form-control" name="rating">
            <option value="0">*</option>
            <option value="1">**</option>
            <option value="2">***</option>
            <option value="3">****</option>
            <option value="4">*****</option>
        </select>
    </div>
</div> -->
<!-- End Rating Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add New Item" class="btn btn-primary btn-sm" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>

<!--//~End Add -->
<?php
    } elseif ($do == "Insert") { //~ Insert Item Page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //^ To Protect Website So It's Inside Request Method
            
            echo '<h1 class="text-center">Update Item</h1>';
            
            echo "<div class='container'>"; // Start Container

            // Get Variables From The Form

            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['members'];
            $cat        = $_POST['categories'];
            $tags       = $_POST['tags'];
            
        
            // Validation Form (Server-Side)

            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = "Name Can't Be <strong>Empty</strong>";
            }
            if (empty($desc)) {
                $formErrors[] = "Description Can't Be <strong>Empty</strong>";
            }
            if (empty($price)) {
                $formErrors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if (empty($country)) {
                $formErrors[] = "Country Can't Be <strong>Empty</strong>";
            }
            if ( $status == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Status</strong>";
            }
            if ( $member == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Member</strong>";
            }
            if ( $cat == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Category</strong>";
            }
            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error):
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            endforeach;

            // Check If There's No Error Proceed The Update Operation

            if (empty($formErrors)):
                // Insert User Info In The Database
                
                $stmt = $con->prepare("INSERT INTO 
                                            items(`Name`, `Description`, Price, Country_Made, `Status`, Add_Date, Cat_ID, Member_ID, tags)
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now() , :zcat, :zmember, :ztags) "); 
                            // now() is mysql feature , add from admin panel so user should be approved

                $stmt->execute(array(
                
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zcat'      => $cat,
                    'zmember'  => $member,
                    'ztags'    => $tags
                    
                ));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                redirectHome($theMsg, 'back');

            endif;
            }else{ // If Try Open Without Request Method
            echo '<div class="container">';

                $theMsg = "<div class='alert alert-danger'>Sorry, You Can't Browser This Page Directory</div>";
                
                redirectHome($theMsg); // back > redirect to previous page
            echo '</div>';
        }
        echo '</div>'; // Close Container

        //~ End Insert Page

    } elseif ($do == 'Edit') { //~ Edit Page

        
            // Check If Get Request items Is Numeric & Get The Integer Value Of It

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 

            // Select All Data Depend On This ID

        $stmt = $con->prepare(" SELECT * FROM items WHERE `Item_ID` = ?");

            // Execute Query

        $stmt->execute(array($itemid));
        $item = $stmt->fetch(); // Fetch Data From Query In Array
        $count = $stmt->rowCount(); // Collect How Much Row In Query
        
            // If There's Such ID Show The Form >>

        if($count > 0 ){ ?>

        <h1 class="text-center">Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item" value="<?php echo $item['Name']; ?>"/>
                    </div>
                </div>
                <!-- End Name Field -->

                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item" value="<?php echo $item['Description']; ?>"/>
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item" value="<?php echo $item['Price']; ?>"/>
                    </div>
                </div>
                <!-- End Price Field -->
                <!-- Start Country Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" required="required" placeholder="Country of Made" value="<?php echo $item['Country_Made']; ?>"/>
                    </div>
                </div>
                <!-- End Country Field -->
                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="1" <?php if ( $item['Status'] == 1) { echo 'selected';} ?>>New</option>
                            <option value="2" <?php if ( $item['Status'] == 2) { echo 'selected';} ?>>Like New</option>
                            <option value="3" <?php if ( $item['Status'] == 3) { echo 'selected';} ?>>Used</option>
                            <option value="4" <?php if ( $item['Status'] == 4) { echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status Field -->
                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM users ");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='" . $user["UserID"] . "'";
                                        if( $item['Member_ID'] == $user["UserID"] ) { echo 'selected'; } 
                                    echo ">" . $user["Username"] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members Field -->
                <!-- Start Category Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM categories ");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat["ID"] . "'";
                                        if( $item["Cat_ID"] == $cat["ID"] ) { echo "selected"; }
                                    echo ">" . $cat["Name"] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Field -->

                <!-- Start Tags Field -->
                <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" 
                            value="<?php echo $item['tags']; ?>"/>
                        </div>
                    </div>
                    <!-- End Tags Field -->

                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
                    </div>
                </div>
            </form>
                <!-- End Submit Field -->
                <!-- Start Comment -->
            <?php
            // Select All Comments Except Admin
            $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username AS Member
                                    FROM comments
                                    INNER JOIN users ON
                                    users.UserID = comments.user_id WHERE item_id = ?;
                                    ");

            // Execute The Statement
            $stmt->execute(array($itemid));

            // Assign To Variable
            $rows = $stmt->fetchAll();
            
            if (! empty($rows)) {

            ?>

                <h1 class="text-center">Manage [ <?php echo $item['Name']?> ] Comments</h1>
                    <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>User name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <!-- LOOP -->
                        <?php
                        foreach ($rows as $row) :
                            echo '<tr>';
                            echo '<td>' . $row["comment"] . '</td>';
                            echo '<td>' . $row["Member"] . '</td>';
                            echo '<td>' . $row["comment_date"]     .  '</td>';
                            echo '<td>
                                    <a href="comments.php?do=Edit&comid=' . $row["c_id"] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="comments.php?do=Delete&comid=' . $row["c_id"] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';

                            if ($row['status'] == 0) {

                                echo '<a 
                                        href="comments.php?do=Approve&comid=' . $row["c_id"] . '" 
                                        class="btn btn-info activate">
                                        <i class="fa fa-check"></i> Approve</a>';
                            }

                            echo '</td>';
                            echo '</tr>';
                        endforeach;
                        ?>
                    </table>
                </div>
                <?php } ?>
            </div>

    <?php 
            // If There;s No Such ID Show Error Message
        }
        else{
        echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">There\'s No Such ID</div>';
            redirectHome($theMsg);
        echo '</div>';
        }

        //~ End Edit Page
    } //* Close Edit
    elseif ($do == 'Update') { //~ Update Page

        echo '<h1 class="text-center">Update Item</h1>';

        echo "<div class='container'>"; // Start Container

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            //^ This Data Come From input > name (input.name)
            $id       = $_POST['itemid'];
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];

            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $cat      = $_POST['category'];
            $member   = $_POST['member'];
            $tags     = $_POST['tags'];

            // Validation Form (Server-Side)

            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = "Name Can't Be <strong>Empty</strong>";
            }
            if (empty($desc)) {
                $formErrors[] = "Description Can't Be <strong>Empty</strong>";
            }
            if (empty($price)) {
                $formErrors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if (empty($country)) {
                $formErrors[] = "Country Can't Be <strong>Empty</strong>";
            }
            if ( $status == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Status</strong>";
            }
            if ( $member == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Member</strong>";
            }
            if ( $cat == 0 ) { // Not Work If ===
                $formErrors[] = "You Must Choose <strong>Category</strong>";
            }

            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error):
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            endforeach;

            // Check If There's No Error Proceed The Update Operation
            
            if (empty($formErrors)):

                // Update The Database With Info
                $stmt = $con->prepare("UPDATE 
                                            items 
                                        SET 
                                            `Name` = ?, `Description` = ?, Price = ?, Country_Made = ?,`Status` = ?, Cat_ID = ?, Member_ID = ?, tags = ?
                                        WHERE Item_ID = ?");

                $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                // Echo Success Message
            echo '<div class="container">';

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                
                redirectHome( $theMsg , 'back');

            echo '</div>';
            endif;

        }else{ // If Try Open Without Request Method
            echo '<div class="container">';
                $theMsg = "<div class='alert alert-danger'>Sorry, You Can't Browser This Page Directory</div>";
                redirectHome($theMsg);
            echo '</div>';
        }
        echo '</div>'; // Close Container
    //~ End Update Page

    } 
    elseif ($do == "Delete") { //~ Delete Page

        echo '<h1 class="text-center">Delete Item</h1>';

        echo "<div class='container'>"; // Start Container

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 

                // Select All Data Depend On This ID

            $check = checkItem('Item_ID', 'items', $itemid);
            
                // If There's Such ID Show The Form >>

            if($check > 0 ){ 

                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");

                $stmt->bindParam(":zid" , $itemid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";

                redirectHome($theMsg, 'back'); // right back to his page
            }
                else {
                        $theMsg = "<div class='alert alert-danger'>There ID Is Not Exist</div>";
                        redirectHome($theMsg);
                }
                    echo "</div>";              // End Container

        //~ End Delete Page

    } elseif ( $do == "Approve") { //~ Start Approve Page

        echo '<h1 class="text-center">Approve Item</h1>';

        echo "<div class='container'>"; // Start Container

            // Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; 

            // Select All Data Depend On This ID

            $check = checkItem('Item_ID', 'items', $itemid);
            
                // If There's Such ID Show The Form >>

            if($check > 0 ){ 

                $stmt = $con->prepare("UPDATE Items SET Approve = 1 WHERE Item_ID = ?");

                $stmt->execute(array($itemid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Activate</div>";

                redirectHome($theMsg, 'back');
        }else {
            $theMsg = "<div class='alert alert-danger'>There ID Is Not Exist</div>";
            redirectHome($theMsg);
        }
        echo "</div>";  //* End Container
    } //~ End Approve Page
    include $tpl . 'footer.php';
} else { // If Not Sign-In 

    header('Location: index.php');

    exit(); // Or die();
}

ob_end_flush(); // Release The Output
?>