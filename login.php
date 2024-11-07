<?php 
ob_start();

session_start();
$pageTitle = 'Login';

if (isset($_SESSION['User'])) {
    header('Location: index.php'); // Redirect To index Page
}

include 'init.php';

// Check If User Coming From HTTP Post Request

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);
        
        // Check If The User Exist In Database
        
        //~ $stmt => statement , $con    came from connect.php , prepare() = help to stop bugs and make calculate before get in database that how to protect it
        $stmt = $con->prepare("
                    SELECT 
                        UserID, `Username`, `Password` 
                    FROM 
                        users 
                    WHERE 
                        `Username` = ? 
                    AND 
                        `Password` = ? 
        ");

        $stmt->execute(array($user, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount(); // Collect How Much Row In Query
        
        // If Count > 0 This Mean Database Contain This Record About This Username
        if ($count > 0){
            // echo "Welcome Back " . $username;
            $_SESSION['user'] = $user;   // Register Session Name
            $_SESSION['uid']  = $row['UserID']; // Register User ID In Session
            header('Location: index.php');   // Redirect To index Page
            exit();
        }
    }else { 
        $formErrors= array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email    = $_POST['email'];

        // Filter User
        if (isset($_POST['username'])){
            $filterUser = strip_tags(filter_var(htmlspecialchars($username, ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)); // Work Too
// $filterUser = htmlspecialchars((strip_tags($_POST['username'])), ENT_QUOTES, 'UTF-8'); // Work Too
// FILTER_SANITIZE_STRING deprecate
            if ( strlen($filterUser) < 4 ) {
                $formErrors[] = 'Username Must Be Larger Than 4 Characters.';
            }
        }
        // Validation  Password
        if (isset($password) && isset($password2) ){
            if (empty($password)) {
                $formErrors[] = 'Sorry, Password Can\'t Be Empty.';
            }
            if ( sha1($password) !== sha1($password2) ) {
                $formErrors[] = 'Sorry, Password Is Not Match.';
            }
        }
        // Filter & Validation Email
        if (isset($email)){
            $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if ( filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) { // This Function To Check Email Is Valid
                $formErrors[] = 'Sorry, Email Is Not Valid.';
            }
        }

        // Check If There's No Error Proceed The User Add
        if (empty($formErrors)){

            // Check If User Exist in Database
                            // Select    , Table , Value|Point Of Search
            $check = checkItem("Username", "users", $username);
            if ( $check == 1) {
                
                $formErrors[] = "Sorry, This User Is Exist";
            }

        // Insert User Info In The Database

        $stmt = $con->prepare("INSERT INTO 
                                    users(Username, `Password`, Email, RegStatus, Date)
                                VALUES(:zuser, :zpass, :zmail, 0, now()) "); 
                    // now() is mysql feature , add from admin panel so user should be approved

        $stmt->execute(array(
        
            'zuser' => $username,
            'zpass' => sha1($password),
            'zmail' => $email
        ));

        // Echo Success Message
        $successMsg = 'Congrats, You Are Now Registered User';
        }
    }
}
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="signup">Signup</span>
    </h1>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input
                
                class="form-control"
                type="text"
                name="username"
                autocomplete="off"
                placeholder="Type your username" required />
        </div>
        <div class="input-container">
            <input
                class="form-control"
                type="password"
                name="password"
                autocomplete="new-password"
                placeholder="Type your password" required />
        </div>
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
    </form>
    <!-- End Login Form -->
    <!-- Start Signup Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input
                pattern=".{4,}"
                title="Username Must Be Larger Than 4 Characters"
                class="form-control"
                type="text"
                name="username"
                autocomplete="off"
                placeholder="Type your username" required/> 
        </div>
        <div class="input-container">
            <input
                minlength="4"
                class="form-control"
                type="password"
                name="password"
                autocomplete="new-password"
                placeholder="Type a complex password" required/>
        </div>
        <div class="input-container">
            <input
                minlength="4"
                class="form-control"
                type="password"
                name="password2"
                autocomplete="new-password"
                placeholder="Type a password again" required/>
        </div>
        <div class="input-container">
            <input
                class="form-control"
                type="email"
                name="email"
                placeholder="Type a valid email" required/>
        </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
    </form>
    <!-- End Signup Form -->
    <div class="the-errors text-center">
        <?php 
            if (!empty($formErrors)){
                foreach ($formErrors as $error){
                    echo '<div class="msg">' . $error . '</div>';
                }
            }
            if (isset($successMsg)){
                echo '<div class="msg success">' . $successMsg . '</div>';
            }
            $formErrors[] = array(); // To Clear Form Errors
        ?>
    </div>
</div>

<?php include $tpl . 'footer.php';
ob_end_flush();

?>