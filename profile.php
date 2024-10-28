<?php
    session_start();
    
    $pageTitle = 'Profile';

    include 'init.php';
    if (isset($_SESSION['user'])) {
?>

<h1 class="text-center">My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                Name : Mohamed
            </div>
        </div>
    </div>
</div>

<div class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <div class="panel-body">
                Test Ads
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
            Test Comments
            </div>
        </div>
    </div>
</div>

<?php
    }else {
        header('Location: login.php');
        exit();
    }
    include $tpl . 'footer.php';
?>