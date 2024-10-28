<?php 
    // Also Will Upload With init file + Make index file clean
    include 'connect.php'; // Put All Files Important header

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

    // Include Navbar On All Pages Except The One With $noNavbar Variable
    if (!isset($noNavbar)) { include $tpl . 'navbar.php'; };

    ?>