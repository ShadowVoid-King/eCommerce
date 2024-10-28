<!-- Header -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />
</head>

<body>

    <!-- Upper Bar -->
    <div class="upper-bar">
        <div class="container">
            <?php 
                if (isset($_SESSION['user'])) {
                    echo 'Welcome ' . $sessionUser . ' ';
                    echo '<a href="profile.php">My Profile</a>';
                    echo ' - <a href="logout.php">Logout</a>';

                    $userStatus = checkUserStatus($sessionUser);
                    if ($userStatus == 1) {
                        // User Is Not Activated , Also Can used for banned pages for users
                    }
                }else {
            ?>
            <a href="login.php">
                <span class="pull-right">Login/Signup</span>
            </a>
            <?php } ?>
        </div>
    </div>
    <!-- Navbar -->
    <!-- https://getbootstrap.com/docs/3.3/components/#navbar -->
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php #echo lang('Home_Admin'); ?>Homepage</a>
            </div>

            <div class="collapse navbar-collapse navbar-right" id="app-nav">
                <ul class="nav navbar-nav">
                    <?php 
                    // only one page [categories] and and id page will show there *No Needed To Create Each One
                    //Str_replace Will Replace Space By - So It Can Readable
                    foreach (getCat() as $cat) {
                        echo "<li>
                            <a href='categories.php?pageid=" . $cat["ID"] . "&pagename=" . str_replace(' ', '-', $cat["Name"]) . "'>" 
                            . $cat["Name"] . "
                            </a>
                        </li>"; 
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>