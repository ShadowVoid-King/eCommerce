<?php
/* 

================================================================================
===                         Manage Members Pages                             ===
===             You Can Add | Edit | Delete Members From Here                ===
================================================================================

*/

session_start();

$pageTitle = 'Members';

if (isset($_SESSION['Username'])){
    
    include 'init.php';
    
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // Check Query
    
    // Start Manage Page

    // If The Page Is Main Page

    if ( $do == 'Manage') {                                                             //~ Manage Members Page 
    

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == "Pending") {

            $query = 'AND RegStatus = 0'; // This Code Will Show Only Not Approval Users And Add It To Query

        }

        // Select All Users Except Admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        
        // Execute The Statement
        $stmt->execute();
        
        // Assign To Variable
        $rows = $stmt->fetchAll();
        if (! empty($rows)) {
    ?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <!-- LOOP -->
                    <?php
                        foreach ($rows as $row) :
                            echo '<tr>';
                            echo '<td>' . $row["UserID"]   . '</td>';
                            echo '<td>' . $row["Username"] . '</td>';
                            echo '<td>' . $row["Email"]    . '</td>';
                            echo '<td>' . $row["FullName"] . '</td>';
                            echo '<td>' . $row["Date"]     .  '</td>';
                            echo '<td>
                                <a href="members.php?do=Edit&userid=' . $row["UserID"] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="members.php?do=Delete&userid=' . $row["UserID"] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';

                                if ( $row['RegStatus'] == 0 ) {

                                    echo '<a 
                                    href="members.php?do=Activate&userid=' . $row["UserID"] . '" 
                                    class="btn btn-info activate">
                                    <i class="fa fa-check"></i> Activate</a>';

                                }

                            echo '</td>';
                            echo '</tr>';
                        endforeach;
                    ?>
                </table>
            </div>
            
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a> <!-- For Extra Space -->
            
        </div>                                                                         
        <?php } else {
            echo '<div class="nice-message">There\'s No Members To Show</div>';
            echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
        } 
        ?> <!--//~ End Manage Page -->

    <?php }elseif ( $do == 'Add' ){                                                         //~ Add Page ?>
            
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- <input type="hidden" name="userid" value="<?php #echo $userid; ?>"> You Don't Need userId Because You Gone To Make one and Database Will Create it Auto--> 
                        <!-- There is no need for value -->
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login To Shop"/>
                        </div>
                    </div>
                    <!-- End Username Field -->
                    
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                                                            <!-- If Input Is (Null) Or (Not Update) so The Value Will Be [Old Password]  -->
                            <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password Must Be Hard & Complex" />
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
                        </div>
                    </div>
                    <!-- End Email Field -->
                    
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add New Member" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>


    <?php
        }elseif ( $do == "Insert") {                                                        //~ Insert Member Page
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //^ To Protect Website So It's Inside Request Method
            
            echo '<h1 class="text-center">Update Member</h1>';
            
            echo "<div class='container'>"; // Start Container

            // Get Variables From The Form
                //^ This Data Come From input > name (input.name)
            $user   = $_POST['username'];
            $pass   = $_POST['password'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];
            
            $hashPass = sha1($pass);
        
            // Validation Form (Server-Side)

            $formErrors = array();

            if (strlen($user) < 4) {
                $formErrors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if (strlen($user) > 20) {
                $formErrors[] = "Username Can't Be More Than <strong>20 Characters</strong>";
            }
            if (empty($user)) {
                $formErrors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if (empty($pass)) {
                $formErrors[] = "Password Can't Be <strong>Empty</strong>";
            }
            if (empty($email)) {
                $formErrors[] = "Email Can't Be <strong>Empty</strong>";
            }
            if (empty($name)) {
                $formErrors[] = "Full Name Can't Be <strong>Empty</strong>";
            }
            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error):
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            endforeach;

            // Check If There's No Error Proceed The Update Operation

            if (empty($formErrors)):

                $stmt2 = $con->prepare("SELECT
                                            *
                                        FROM
                                            Users
                                        WHERE 
                                            Username = ?
                                        AND
                                            UserId != ?
                                            ");
                
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                // If Member Exist And His ID Not ItSelf So It Look Only On Username
                if ( $count == 1) {
                    echo "<div class='alert alert-danger'>Sorry, This User Is Exist</div>";
                    redirectHome($theMsg, 'back');
                } else {
                        // Check If User Exist in Database
                                        // Select    , Table , Value|Point Of Search
                        $check = checkItem("Username", "users", $user);
                        if ( $check == 1) {
                            
                            $theMsg = "<div class='alert alert-danger'>Sorry, This User Is Exist</div>";
                            redirectHome($theMsg, 'back');
                    }
            }else:

                    // Insert User Info In The Database
                    
                    $stmt = $con->prepare("INSERT INTO 
                                                users(Username, `Password`, Email, FullName, RegStatus, Date)
                                            VALUES(:zuser, :zpass, :zmail, :zname, 1, now()) "); 
                                // now() is mysql feature , add from admin panel so user should be approved

                    $stmt->execute(array(
                    
                        'zuser' => $user,
                        'zpass' => $hashPass,
                        'zmail' => $email,
                        'zname' => $name
                        
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
        }elseif ( $do == 'Edit' ) {                                                           //~ Edit Page
    
            // Check If Get Request userid Is Numeric & Get The Integer Value Of It

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; 

            // Select All Data Depend On This ID

        $stmt = $con->prepare(" SELECT * FROM users WHERE `UserID` = ? LIMIT 1");

            // Execute Query

        $stmt->execute(array($userid));
        $row = $stmt->fetch(); // Fetch Data From Query In Array
        $count = $stmt->rowCount(); // Collect How Much Row In Query
        
            // If There's Such ID Show The Form >>

        if($count > 0 ){ ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>" />
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- End Username Field -->
                    
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                                                            <!-- If Input Is (Null) Or (Not Update) so The Value Will Be [Old Password]  -->
                            <input type="hidden" name="old-password" value="<?php echo $row['Password']; ?>" />
                            <input type="password" name="new-password" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want Change" />
                        </div>
                    </div>
                    <!-- End Password Field -->
                    
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required" />
                        </div>
                    </div>
                    <!-- End Email Field -->
                    
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>" required="required" />
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
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
    }elseif ( $do == 'Update' ) {                                                           //~ Update Page

        echo '<h1 class="text-center">Update Member</h1>';

        echo "<div class='container'>"; // Start Container

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form
                //^ This Data Come From input > name (input.name)
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            // Password Trick
                //^ Don't Forget add (sha1)
            $pass = empty($_POST['new-password']) ? $_POST['old-password'] : sha1($_POST['new-password']);

            // Validation Form (Server-Side)

            $formErrors = array();

            if (strlen($user) < 4) {
                $formErrors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if (strlen($user) > 20) {
                $formErrors[] = "Username Can't Be More Than <strong>20 Characters</strong>";
            }
            if (empty($user)) {
                $formErrors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if (empty($email)) {
                $formErrors[] = "Email Can't Be <strong>Empty</strong>";
            }
            if (empty($name)) {
                $formErrors[] = "Full Name Can't Be <strong>Empty</strong>";
            }
            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error):
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            endforeach;

            // Check If There's No Error Proceed The Update Operation
            
            if (empty($formErrors)):

                // Update The Database With Info
                                    //! Don't Write '' in names of Columns Fields Like 'Username' Don't Work
                $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, `Password` = ? WHERE UserID = ?");

                $stmt->execute(array($user, $email, $name, $pass, $id));

                // Echo Success Message
            echo '<div class="container">';

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                
                redirectHome( $theMsg , 'back', 4);

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
    } elseif ( $do == "Delete") {                                                                //~ Delete Page

        echo '<h1 class="text-center">Delete Member</h1>';

        echo "<div class='container'>"; // Start Container

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; 

                // Select All Data Depend On This ID

            //* $stmt = $con->prepare(" SELECT * FROM users WHERE `UserID` = ? LIMIT 1");
            
            // Execute Query
            
            //* $stmt->execute(array($userid));
            
            //*$count = $stmt->rowCount(); // Collect How Much Row In Query
            
            // Replace All $stmt from Above with one line

            $check = checkItem('userid', 'users', $userid);
            
                // If There's Such ID Show The Form >>

            if($check > 0 ){ 

                $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser");

                $stmt->bindParam(":zuser" , $userid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";

                redirectHome($theMsg, 'back'); // right back to his page
            }
                else {
                        $theMsg = "<div class='alert alert-danger'>There ID Is Not Exist</div>";
                        redirectHome($theMsg);
                }
                    echo "</div>";              // End Container

    }elseif ( $do == "Activate") {                                                          //~ Start Activate Page

        echo '<h1 class="text-center">Activate Member</h1>';

        echo "<div class='container'>"; // Start Container

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; 

            // Select All Data Depend On This ID

            $check = checkItem('userid', 'users', $userid);
            
                // If There's Such ID Show The Form >>

            if($check > 0 ){ 

                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

                $stmt->execute(array($userid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Activate</div>";

                redirectHome($theMsg, 'back');
        }else {
            $theMsg = "<div class='alert alert-danger'>There ID Is Not Exist</div>";
            redirectHome($theMsg);
        }
        echo "</div>";  //* End Container
    }                                                                                       //~ End Activate Page
        include $tpl . 'footer.php'; //! Error Make Dropdown Not Work Because Need Js For Event
    }else{ // If Not Sign-In 
        header('Location: index.php');
        exit(); // Or die();
    }                                       //* Close isset Username
?>