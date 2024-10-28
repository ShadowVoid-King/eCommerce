<?php

/* 
** Get Categories Function v2.0
** Function To Get Categories From Database
*/

function getCat() {
    global $con;

    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

    $getCat->execute();

    $cats = $getCat->fetchAll();

    return $cats;
}

/* 
** Get Items Function v2.0
** Function To Get Items From Database
*/

function getItems($CatID) {
    global $con;

    $getItems = $con->prepare("SELECT * FROM items WHERE Cat_ID = ? ORDER BY Item_ID DESC");

    $getItems->execute(array($CatID));

    $items = $getItems->fetchAll();

    return $items;
}

/* 
** Check Status Function v1.o
** Check If Users Is Activated
** Function To Check The RegStatus Of The User
*/

function checkUserStatus($user) {
    global $con;
    $stmtx = $con->prepare("
    SELECT 
        `Username`, `RegStatus` 
    FROM 
        users 
    WHERE 
        `Username` = ? 
    AND 
        `RegStatus` = 0 
    ");

    $stmtx->execute(array($user));
    $status = $stmtx->rowCount(); // Collect How Much Row In Query
    // 1 = No Activate , 0 Activate
    return $status;
}


// -----------------------------------------------------------------------------

/* 
    ** getTitle v1.0
    ** Title Function That Echo The Page Title in Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Pages
*/

function getTitle() {

    global $pageTitle; // To Make It Accessible To All Files , Pages
    
    if (isset($pageTitle)) {
        echo $pageTitle;
    }else {
        echo 'Default';
    }
}

/* 
** Home Redirect Function v2.0
**  This Function Accept Parameters
** $theMsg  = Echo The Message [ Error | Success | Warning ]
** $url     = The Link You Want To Redirect To
** $seconds = Seconds Before Redirecting
*/

function redirectHome($theMsg, $url = null ,$seconds = 3) {

    if ( $url == null ) {

        $url = "index.php";

        $link = "Homepage";

    }elseif (isset($_SERVER['HTTP_REFERER']) && $_SERVER["HTTP_REFERER"] !== "") {

        $url = $_SERVER["HTTP_REFERER"];

        $link = "Previous Page";

    }else {

        $url = "index.php";

        $link = "Homepage";

    }
    echo $theMsg;

    echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds</div>";

    header("refresh:$seconds; url = $url");
}

/* 
** Check Item Function v1.0
** Function To Check Item In Database [ Function Accept Parameters ]
** $select = The Item To Select [ Example: user, item, category ]
** $from = The Table To Select From [ Example: users, items, categories ]
** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
*/

function checkItem($select, $from, $value) {
    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?"); // We Can Change ? to $value

    $statement->execute(array($value));
//^ returns the number of rows affected by an INSERT, UPDATE, or DELETE operation. It counts the number of rows, not columns.
    $count = $statement->rowCount();

    return $count;
}

/* 
** Check Number Of Items v1.0
** Function To Count Number Of Items Rows
** $item   = The Item To Count Exp : Username or UserID
** $table  = The Table To Choose From Exp : users
*/

function countItems($item, $table) {

    global $con; // because you need to make it accessible on function here

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    
    $stmt2->execute();
//^ User This Method To retrieve just the data from one specific column , which is useful if you only need a single value from your query, avoiding fetching all columns unnecessarily.
    echo $stmt2->fetchColumn(); 
}

/* 
** Get Latest Records Function v2.0
** Function To Get Latest Items From Database [ Users, Items, Comments ]
** $select = Field TO Select
** $table = The Table To Choose From
** $order = The DESC Ordering
** $limit = Number Of Records
**- Remove Admin From Show In List
*/

function getLatest($select, $table, $order, $limit = 5, $where = "WHERE GroupID != 1") {
    global $con;

    $statement = $con->prepare("SELECT $select FROM $table $where ORDER BY $order DESC LIMIT $limit ");

    $statement->execute();

    $rows = $statement->fetchAll();

    return $rows;
}

?>