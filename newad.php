<?php
session_start();
$pageTitle = 'Create New Item';
include 'init.php';
// echo $sessionUser;
if (isset($_SESSION['user'])) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $formErrors = array();

        $name = filter_var($_POST["name"],FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST["description"],FILTER_SANITIZE_STRING);
        $price =filter_var($_POST["price"],FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST["country"],FILTER_SANITIZE_STRING);
        $status = filter_var($_POST["status"],FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST["category"],FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST["tags"],FILTER_SANITIZE_STRING); 

        
        if (strlen($name) < 4) {
            $formErrors[]= 'name cant be less than 4 characters';
        }

        if (strlen($desc) < 10) {
            $formErrors[]= 'desc cant be less than 10 characters';
        }

        if (strlen($country) < 2) {
            $formErrors[]= 'country cant be less than 2 characters';
        }
        
        if (empty($price)) {
            $formErrors[]= 'price cant be empty';
        }

        if (empty($status)) {
            $formErrors[]= 'status cant be empty';
        }

        if (empty($category)) {
            $formErrors[]= 'category cant be empty';
        }

        if (empty($formErrors)) {
     
            $stmt = $conn->prepare("INSERT INTO `items`( `Name`, `Description`, `Price`, `Country_Made`, `Status`, `Add_Date`,`Cat_ID`,`Member_ID`,`tags`) VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags)");
            $stmt->execute(array(
                'zname'=>$name,
                'zdesc'=>$desc,
                'zprice'=>$price,
                'zcountry'=>$country,
                'zstatus'=>$status,
                'zmember'=>$_SESSION['uid'] ,
                'zcat'=>$category,
                'ztags'=>$tags));

                if ($stmt) {
                    $succesMsg= "Item Added";
                }
                
                
            }

    }

    ?>
<h1 class="text-center"><?php echo $pageTitle;?></h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create new ad</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method='POST'>
                
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                <input pattern=".{4,}" title="This Field Require At Least 4 Char" type="text" data-class=".live-title" name="name" class="form-control live" autocomplete="off"  required="required" placeholder="name of the Item" />
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                <input pattern=".{10,}" type="text"  data-class=".live-desc" name="description" class="form-control live" autocomplete="off"  required="required" placeholder="Description of the Item" />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                <input type="text" data-class=".live-price" name="price" class="form-control live" autocomplete="off"  required="required" placeholder="price of the Item" />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country of made</label>
                                <div class="col-sm-10 col-md-9">
                                <input type="text" name="country" class="form-control" autocomplete="off"  required="required" placeholder="Country of made" />
                                </div>
                            </div>


                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category" required>
                                        <option value="">...</option>
                                            <?php
                                                    $allItems = getAllFrom('*','items','where  Approve = 1','','Item_ID','ASC');
                                                    foreach ($cats as $cat) {
                                                        echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                                    }
                                            
                                            ?>
                                        </select>
                                </div>
                            </div>


                            
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Tags</label>
                    <div class="col-sm-10 col-md-9">
                    <input value="<?php echo $item['tags']?>"  type="text" name="tags" class="form-control" autocomplete="off"  placeholder="Seperate tags with comma (,)" />
                    </div>
                </div>






                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="status">
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Very Old</option>
                                        </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class=" col-sm-offset-3  col-sm-9">
                                <input type="submit" value="Add Item" class="btn btn-primary btn-sm "/>
                                </div>
                            </div>


            </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" src="logo.PNG" alt="">
                            <div class="caption">
                                <h3 class="live-title"></h3>
                                <p class="live-desc"></p>
                            </div>
                            </div>
                        </div>
                </div>
                <!-- start looping through errors -->
                <?php
                    if (! empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo "<div class='alert alert-danger'>".$error . '</div>';
                        }
                    }

                    if (isset($succesMsg)) {

                        echo '<div class="alert alert-success">' . $succesMsg . '</div>';
        
                    }
                ?>
                <!-- end looping through errors -->
            </div>
        </div>
    </div>
</div>
<?php
}else{
    header('location:login.php');
    exit();
}
include $tpl.'footer.php';
?>