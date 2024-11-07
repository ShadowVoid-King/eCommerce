<?php
ob_start();

session_start();

$pageTitle = 'Create New Item';

include 'init.php';
if (isset($_SESSION['user'])) {
    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($_SESSION['user']));
    $info = $getUser->fetch();
/* 
preg_match 
preg_replace
*/
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors  = array();
        $name       = htmlspecialchars(strip_tags($_POST['name']), ENT_QUOTES, 'UTF-8');
        $desc       = htmlspecialchars(strip_tags($_POST['description']), ENT_QUOTES, 'UTF-8');
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = htmlspecialchars(strip_tags($_POST['country']), ENT_QUOTES, 'UTF-8');
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = htmlspecialchars(strip_tags($_POST['tags']), ENT_QUOTES, 'UTF-8');

        if (strlen($name) < 4) {
            $formErrors[] = 'Item Name Must Be  At Least <strong>4</strong> Characters';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Item Description Must Be At Least <strong>10</strong> Characters';
        }
        if (strlen($country) < 2) {
            $formErrors[] = 'Country Must Be At Least <strong>2</strong> Characters';
        }
        if (empty($price)) {
            $formErrors[] = 'Price Must Be Not <strong>Empty</strong>';
        }
        if (empty($status)) {
            $formErrors[] = 'Status Must Be Not <strong>Empty</strong>';
        }
        if (empty($category)) {
            $formErrors[] = 'Country Must Be Not <strong>Empty</strong>';
        }
        //* Insert Page Into Database
        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)):
            // Insert User Info In The Database
            
            $stmt = $con->prepare("INSERT INTO 
                                        items(`Name`, `Description`, Price, Country_Made, `Status`, Add_Date, Cat_ID, Member_ID, tags)
                                    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now() , :zcat, :zmember, :ztags) "); 
                        // now() is mysql feature , add from admin panel so user should be approved

            $stmt->execute(array(
            
                'zname'    => $name,
                'zdesc'    => $desc,
                'zprice'   => $price,
                'zcountry' => $country,
                'zstatus'  => $status,
                'zcat'     => $category,
                'zmember'  => $_SESSION['uid'],
                'ztags'    => $tags
                
            ));
            // Echo Success Message
            if ($stmt) {
                $successMsg = 'Item Has Been Added';
            }
        endif; 
    }
?>

    <h1 class="text-center"><?php echo $pageTitle; ?></h1>

    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle; ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <!-- Start Name Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                    pattern=".{4,}"
                                    title="This Field Require At Least 4 Characters"
                                    type="text" name="name" class="form-control live live-name" 
                                    required placeholder="Name of The Item" data-class=".live-title"/>
                                </div>
                            </div>
                            <!-- End Name Field -->
                            <!-- Start Description Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                    pattern=".{10,}"
                                    title="This Field Require At Least 10 Characters"
                                    type="text" name="description" class="form-control live live-desc" 
                                    required placeholder="Description of The Item" data-class=".live-desc"/>
                                </div>
                            </div>
                            <!-- End Description Field -->
                            <!-- Start Price Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="number" name="price" class="form-control live live-price" 
                                    required placeholder="Price of The Item" data-class=".live-price"/>
                                </div>
                            </div>
                            <!-- End Price Field -->
                            <!-- Start Country Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="country" class="form-control" 
                                    required placeholder="Country of Made" />
                                </div>
                            </div>
                            <!-- End Country Field -->
                            <!-- Start Status Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="status" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Very Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->
                            <!-- Start Category Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category" required>
                                        <option value="">...</option>
                                        <?php 
// $stmt = $con->prepare("SELECT * FROM categories");
// $stmt->execute();
// $cats = $stmt->fetchAll();
                                            $cats = getAllFrom('*', 'categories', '', '','ID');
                                            foreach ($cats as $cat) {
                                                echo "<option value='" . $cat["ID"] . "'>" . $cat["Name"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Category Field -->
                            <!-- Start Tags Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" />
                                </div>
                            </div>
                    <!-- End Tags Field -->
                            <!-- Start Submit Field -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="Add New Item" class="btn btn-primary btn-sm" />
                                </div>
                            </div>
                            <!-- End Submit Field -->
                    </form>
                        </div>
                        <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                        <span class="price-tag">
                            $<span class="live-price">0</span>
                        </span>
                        <img class="img-responsive" src="img.png" alt=""/>
                        <div class="caption">
                            <h3 class="live-title">Title</h3>
                            <p class="live-desc">Description</p>
                        </div>
                    </div>
                        </div>
                    </div>
                    <!-- Start Looping Through Errors -->
                    <?php 
                        if (! empty($formErrors)){
                            foreach($formErrors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                        if (isset($successMsg)) {
                            echo '<div class="alert alert-success">'. $successMsg .'</div>';
                        }
                    ?>
                    <!-- End Looping Through Errors -->
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>