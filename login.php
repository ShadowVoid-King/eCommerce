<?php 

session_start();
$pageTitle = 'Login';

if (isset($_SESSION['User'])) {
    header('Location: index.php'); // Redirect To index Page
}

include 'init.php';

// Check If User Coming From HTTP Post Request

if ($_SERVER['REQUEST_METHOD'] == 'POST') :
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);
    
    // Check If The User Exist In Database
    
    //~ $stmt => statement , $con    came from connect.php , prepare() = help to stop bugs and make calculate before get in database that how to protect it
    $stmt = $con->prepare("
    SELECT 
        `Username`, `Password` 
    FROM 
        users 
    WHERE 
        `Username` = ? 
    AND 
        `Password` = ? 
    ");

    $stmt->execute(array($user, $hashedPass));
    $count = $stmt->rowCount(); // Collect How Much Row In Query
    
    // If Count > 0 This Mean Database Contain This Record About This Username
    if ($count > 0):
        // echo "Welcome Back " . $username;
        $_SESSION['user'] = $user;   // Register Session Name
        header('Location: index.php');   // Redirect To index Page
        exit();
    endif;
endif;

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
        <input class="btn btn-primary btn-block" type="submit" value="Login" />
    </form>
    <!-- End Login Form -->
    <!-- Start Signup Form -->
    <form class="signup">
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
                placeholder="Type a complex password" required />
        </div>
        <div class="input-container">
            <input
                class="form-control"
                type="password"
                name="password2"
                autocomplete="new-password"
                placeholder="Type a password again" required />
        </div>
        <div class="input-container">
            <input
                class="form-control"
                type="email"
                name="email"
                placeholder="Type a valid email" required />
        </div>
        <input class="btn btn-success btn-block" type="submit" value="Signup" />
    </form>
    <!-- End Signup Form -->
</div>

<?php include $tpl . 'footer.php';

?>