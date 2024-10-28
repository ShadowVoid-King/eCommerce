<?php 
    session_start();
    $noNavbar = ''; // Prevent Navbar To Upload To Current Page
    $pageTitle = 'Login';
    /*
    ! Session Admin Shouldn't Be Same Name For User 
    *Because User Can Be Admin Without Permission or Steal And They Are Same Name Of Session 
    ^So Best Practices For $_SESSION['Admin'] for admin , $_SESSION['Username'] for user 
    */
    if (isset($_SESSION['Username'])) {
        header('Location: dashboard.php'); // Redirect To Dashboard Page
    }

    include 'init.php';


    // Check If User Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') :
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);
        
        // Check If The User Exist In Database
        
        //~ $stmt => statement , $con    came from connect.php , prepare() = help to stop bugs and make calculate before get in database that how to protect it
        $stmt = $con->prepare("
        SELECT 
            `UserID`, `Username`, `Password` 
        FROM 
            users 
        WHERE 
            `Username` = ? 
        AND 
            `Password` = ? 
        AND 
            GroupID = 1
        LIMIT 1");

        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch(); // Fetch Data From Query In Array
        $count = $stmt->rowCount(); // Collect How Much Row In Query
        
        // If Count > 0 This Mean Database Contain This Record About This Username
        if ($count > 0):
            // echo "Welcome Back " . $username;
            $_SESSION['Username'] = $username;   // Register Session Name
            $_SESSION['ID'] = $row['UserID'];    // Register Session ID
            header('Location: dashboard.php');   // Redirect To Dashboard Page
            exit();
        endif;
    endif;
?>

<!-- Don't Forget action , Method Like Me {-"_"-}=` -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="Post">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" />
    </form>

<?php include $tpl . 'footer.php'; ?>