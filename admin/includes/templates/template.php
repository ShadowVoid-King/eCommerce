<?php
/* 

==================================__________====================================
===                               |Template|                                 ===
===                               |========|                                 ===
==================================||||||||||====================================

*/
ob_start(); // Object Buffering Start * Save Storage On Memory Except Header

session_start();

$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // Check Query

    // If The Page Is Main Page

    if ($do == 'Manage') { //~ Manage Members Page 


        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == "Pending") {

            $query = 'AND RegStatus = 0'; // This Code Will Show Only Not Approval Users And Add It To Query

        }

?>
        <!--//~ End Manage Page -->

    <?php } elseif ($do == 'Add') { //~ Add Page 
    ?>

<?php
    } elseif ($do == "Insert") { //~ Insert Member Page

        //~ End Insert Page

    } elseif ($do == 'Edit') { //~ Edit Page

        //~ End Edit Page
    } //* Close Edit
    elseif ($do == 'Update') { //~ Update Page

    } //~ End Update Page

    elseif ($do == "Delete") { //~ Delete Page

        //~ End Delete Page

    } elseif ($do == "Activate") { //~ Start Activate Page

    } //~ End Activate Page
    include $tpl . 'footer.php';
} else { // If Not Sign-In 

    header('Location: index.php');

    exit(); // Or die();
}

ob_end_flush(); // Release The Output
?>