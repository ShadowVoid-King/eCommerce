<?php
/* 

================================================================================
===                           Categories Pages                               ===
===             =============================================                ===
================================================================================

*/
ob_start(); // Object Buffering Start * Save Storage On Memory Except Header

session_start();

$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // Check Query

    if ($do == 'Manage') { //~ Manage Members Page 

        $sort = 'asc'; // Default

        $sort_array = array ('asc', 'desc');

        if ( isset($_GET['sort']) && in_array( $_GET['sort'] , $sort_array ) ) {
            $sort = $_GET['sort']; // Choose Sort Type
        }

        $stmt2 = $con->prepare("SELECT * FROM Categories WHERE parent = 0 ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll(); 
        
        if ( !empty($cats) ) {
        ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                    <i class='fa fa-sort'></i> Ordering: [ 
                        <a class="<?php if ( $sort == "asc") { echo "active"; } ?>" href="?sort=asc">Asc</a> |
                        <a class="<?php if ( $sort == "desc") { echo "active"; } ?>" href="?sort=desc">Desc</a>
                    ]
                    <i class='fa fa-eye'></i> View: [ 
                        <span class='active' data-view='full'>Full</span> |
                        <span data-view='classic'>Classic</span>
                    ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php 
                    foreach ($cats as $cat) {
                        
                        echo "<div class='cat'>";
                            echo"<div class='hidden-buttons'>";
                                echo "<a href='categories.php?do=Edit&catid=" . $cat["ID"] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                echo "<a href='categories.php?do=Delete&catid=" . $cat["ID"] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";
                            echo "<h3>"   . $cat['Name'] . "</h3>";
                            echo "<div class='full-view'>";
                                echo "<p>";
                                    echo $cat['Description'] == "" ? "This Category Has No Description" : $cat['Description'] ; 
                                echo "</p>";
                                echo $cat['Visibility'] == 1 ? '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>' : 'Visb-ON';
                                echo $cat['Allow_Comment'] == 1 ? '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>' : 'Comment-ON';
                                echo $cat['Allow_Ads'] == 1 ? '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>' : 'Ads-ON';
                                // Get Child Categories
                        $childCats = getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}" , '', 'ID', 'ASC');
                        if ( !empty($childCats)) {
                            foreach ($childCats as $c) {
                                echo '<h4 class="child-head">Child Categories</h4>';
                                echo "<ul class='list-unstyled child-cats'>";
                                    echo "<li class='child-link'>
                                        <a href='categories.php?do=Edit&catid=" . $c["ID"] . "'>". $c["Name"] ."</a>";
                                echo    "<a href='categories.php?do=Delete&catid=" . $c["ID"] . "' class='show-delete confirm'> Delete</a>";
                                    echo "  </li>
                                    </ul>";
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "<hr />";
                    }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href='categories.php?do=Add' ><i class="fa fa-plus"></i> Add New Category</a>
        </div>

        <?php
        } else {
            echo '<div class="container">';
                echo "<div class='alert nice-message'>Sorry, This Category Is Exist</div>";
                echo '<a class="add-category btn btn-primary" href="categories.php?do=Add" ><i class="fa fa-plus"></i> Add New Category</a>';
            echo '</div>';
        }
        //~ End Manage Page -->

    } elseif ($do == 'Add') { //~ Add Page 
?>

        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" />
                    </div>
                </div>
                <!-- End Name Field -->

                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <!-- If Input Is (Null) Or (Not Update) so The Value Will Be [Old Password]  -->
                        <input type="text" name="description" class="form-control" placeholder="Describe The Category" />
                    </div>
                </div>
                <!-- End Description Field -->

                <!-- Start Ordering Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" />
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Category Type -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent ?</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent" id="">
                            <option value="0">None</option>
                            <?php
                                $allCats =getAllFrom('*', 'categories', 'WHERE parent = 0', NULL , 'ID');
                                foreach ($allCats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->
                <!-- Start Visibility Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked/>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1"/>
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility Field -->

                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked/>
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1"/>
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting Field -->

                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked/>
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1"/>
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility Field -->

                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add New Category" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
        
        <!--//~End Add -->
<?php
    } elseif ($do == 'Insert') { //~ Insert Page 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //^ To Protect Website So It's Inside Request Method
            
            echo '<h1 class="text-center">Update Category</h1>';
            
            echo "<div class='container'>"; // Start Container

            // Get Variables From The Form
                //^ This Data Come From input > name (input.name)
            $name        = $_POST['name'];
            $desc        = $_POST['description'];
            $parent      = $_POST['parent'];
            $order       = $_POST['ordering'];
            $visible     = $_POST['visibility'];
            $comment     = $_POST['commenting'];
            $ads         = $_POST['ads'];

            // Check If Category Exist in Database
            //                  Select    , Table , Value|Point Of Search
            $check = checkItem("Name", "categories", $name);

            if ($check == 1) {

                $theMsg = "<div class='alert alert-danger'>Sorry, This Category Is Exist</div>";

                redirectHome($theMsg, 'back');
            } else {
                if ( !empty($name) ) {
                    // Insert Category Info The Database

                    $stmt = $con->prepare("INSERT INTO 
                                        Categories(Name, Description, parent ,Ordering, Visibility, Allow_Comment, Allow_Ads)
                                        VALUES(:zname, :zdesc, :zparent,:zorder, :zvisible, :zcomment, :zads) ");

                    $stmt->execute(array(

                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zparent'   => $parent,
                        'zorder'    => $order,
                        'zvisible'  => $visible,
                        'zcomment'  => $comment,
                        'zads'      => $ads

                    ));
                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                    redirectHome($theMsg, 'back');
                }else {
                    // Echo Error Of Empty Name Message
                    $theMsg = "<div class='alert alert-danger'>Sorry, You Can't Leave This Input Empty</div>";
                    redirectHome($theMsg, 'back');
                }
            }
        } else { // If Try Open Without Request Method
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-danger'>Sorry, You Can't Browser This Page Directory</div>";
            redirectHome($theMsg, 'back'); // back > redirect to previous page
            echo '</div>';
        }
        echo '</div>'; // Close Container

        //~ End Insert Page
    } elseif ($do == 'Edit') {  //~ Edit Page

        
            // Check If Get Request catid Is Numeric & Get The Integer Value Of It

            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ; 

            // Select All Data Depend On This ID

        $stmt = $con->prepare(" SELECT * FROM categories WHERE `ID` = ?");

            // Execute Query

        $stmt->execute(array($catid));

        $cat = $stmt->fetch(); // Fetch Data From Query In Array

        $count = $stmt->rowCount(); // Collect How Much Row In Query
        
            // If There's Such ID Show The Form >>

        if($count > 0 ){ ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat["Name"]; ?>"/>
                    </div>
                </div>
                <!-- End Name Field -->

                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <!-- If Input Is (Null) Or (Not Update) so The Value Will Be [Old Password]  -->
                        <input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat["Description"]; ?>"/>
                    </div>
                </div>
                <!-- End Description Field -->

                <!-- Start Ordering Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat["Ordering"]; ?>"/>
                    </div>
                </div>
                <!-- End Ordering Field -->

                <!-- Start Category Type -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Parent ?</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent" id="">
                            <option value="0">None</option>
                            <?php
                                $allCats =getAllFrom('*', 'categories', 'WHERE parent = 0', NULL , 'ID');
                                foreach ($allCats as $c) {
                                    echo "<option value='". $c['ID'] ."'"; // Double Quote for see variable
                                    if ( $cat['parent'] == $c['ID']) { echo "Selected"; }
                                    echo ">" . $c['Name'] . "</option>";
                                }
                                echo <<<HTML
                                <option value="1">$cat[ID]</option>
                                HTML;
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->

                <!-- Start Visibility Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ( $cat['Visibility'] == 0 ) { echo "checked"; } ?> />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" <?php if ( $cat['Visibility'] == 1 ) { echo "checked"; } ?> />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility Field -->

                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" <?php if ( $cat['Allow_Comment'] == 0 ) { echo "checked"; } ?> />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" <?php if ( $cat['Allow_Comment'] == 1 ) { echo "checked"; } ?>  />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting Field -->

                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" <?php if ( $cat['Allow_Ads'] == 0 ) { echo "checked"; } ?>  />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" <?php if ( $cat['Allow_Ads'] == 1 ) { echo "checked"; } ?>  />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visibility Field -->

                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Update Category" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
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

    } elseif ($do == 'Update') { //~ Update Page

        echo '<h1 class="text-center">Update Category</h1>';

        echo "<div class='container'>"; // Start Container

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
            
            $id     = $_POST['catid'];
            $name   = $_POST['name'];
            $desc   = $_POST['description'];
            $order  = $_POST['ordering'];
            $parent  = $_POST['parent'];

            $visible   = $_POST['visibility'];
            $comment   = $_POST['commenting'];
            $ads       = $_POST['ads'];

                // Update The Database With Info
                $stmt = $con->prepare("UPDATE categories 
                                        SET 
                                            `Name` = ?, 
                                            `Description` = ?, 
                                            Ordering = ?,
                                            parent = ?, 
                                            `Visibility` = ?, 
                                            `Allow_Comment` = ?, 
                                            `Allow_Ads` = ?
                                        WHERE ID = ?");

                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

                // Echo Success Message
            echo '<div class="container">';

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                
                redirectHome( $theMsg , 'back');

            echo '</div>';

        }else{ // If Try Open Without Request Method
            echo '<div class="container">';
                $theMsg = "<div class='alert alert-danger'>Sorry, You Can't Browser This Page Directory</div>";
                redirectHome($theMsg);
            echo '</div>';
        }
        echo '</div>'; // Close Container

        //~ End Update Page
    } elseif ($do == "Delete") { //~ Delete Page

        echo '<h1 class="text-center">Delete Category</h1>';

        echo "<div class='container'>"; // Start Container

            // Check If Get Request catid Is Numeric & Get The Integer Value Of It
                    // catid come from manage url
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; 

                // Select All Data Depend On This ID

            $check = checkItem('ID', 'Categories', $catid);
            
                // If There's Such ID Show The Form >>

            if($check > 0 ){ 

                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");

                $stmt->bindParam(":zid" , $catid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";

                redirectHome($theMsg, 'back');
            }
                else {
                        $theMsg = "<div class='alert alert-danger'>There ID Is Not Exist</div>";
                        redirectHome($theMsg);
                }
                    echo "</div>";              // End Container

        //~ End Delete Page
    }


    include $tpl . 'footer.php';
} else { // If Not Sign-In 

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output
?>