<?php 

ob_start();
session_start();
$pageTitle = 'Categories';


include 'init.php'; 

?>

<div class="container">
    <div class="row">
    <?php 
    
    if(isset($_GET['name'])) {
        $tag = $_GET['name'];
        echo "<h1 class='text-center'>$tag</h1>";
        }
        // %% wild card in SQL and deal like string '%$tag%'
        $tagItems = getAllFrom("*" ,"items", "WHERE tags like '%$tag%'", "AND Approve = 1", "Item_ID");
        // getItems('Cat_ID', $_GET['pageid']);
        foreach( $tagItems as $item ){
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