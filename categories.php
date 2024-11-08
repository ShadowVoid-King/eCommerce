<?php 

ob_start();
session_start();
$pageTitle = 'Categories';


include 'init.php'; 

?>

<div class="container">
    <h1 class='text-center'>Show Category Items</h1>
    <div class="row">
    <?php 
        $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0; 
        $allItems = getAllFrom("*" ,"items", "WHERE Cat_ID = {$category}", "AND Approve = 1", "Item_ID");
        // getItems('Cat_ID', $_GET['pageid']);
        foreach( $allItems as $item ){
            echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                    echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                    echo '<img class="img-responsive" src="img.png" alt=""/>';
                    echo '<div class="caption">';
                        echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name']        . '</a></h3>';
                        echo '<p>'  . $item['Description'] . '</p>';
                        echo '<div class="date">'  . $item['Add_Date'] . '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    ?>
    </div>
</div>

            <!-- echo $item["Name"]; -->


<?php include $tpl . 'footer.php';?>