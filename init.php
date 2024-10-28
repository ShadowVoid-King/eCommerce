<?php 
    // Error Reporting
    ini_set("display_errors", 'On');
    error_reporting(E_ALL);

    // Also Will Upload With init file + Make index file clean
    include 'admin/connect.php'; // Put All Files Important header

    $sessionUser = '';
    if (isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
    }

    // Routes

    $tpl  = 'includes/templates/' ;   // Template Directory
    $lang = 'includes/languages/';   // Language Directory
    $func = 'includes/functions/';  // Function Directory
    $css  = 'layout/css/' ;        // Css Directory
    $js   = 'layout/js/' ;        // Js Directory
    

    // Include The Important Files

    include $func . 'functions.php';
    include $lang. 'english.php'; // * Language Must Be First Before Pages so Didn't get Error
    include $tpl . 'header.php';


    ?>