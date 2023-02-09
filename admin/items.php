<?php 
ob_start();
session_start();
$pageTitle = 'Members';

if(isset($_SESSION['UserName'])){
    
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){



        $stmt = $conn->prepare("SELECT 
                                items.*, categories.Name AS category_name ,users.UserName
                                FROM 
                                items 
                                INNER JOIN 
                                categories 
                                ON 
                                categories.ID = items.Cat_ID 
                                INNER JOIN 
                                users 
                                ON 
                                users.UserID = items.Member_ID
                                ORDER BY item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();

        if (!empty($items)) {
            
        
        
        
        
        ?>
       

       <h1 class="text-center">Mange Items</h1>
       <div class="container">
            <div class="main-table table-responsive  text-center">
                <table class="table table-bordered" >
                    <tr>
                        <td>#item_ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($items as $item) {
                            echo '<tr>';
                                echo '<td>'.$item['item_ID'].'</td>';
                                echo '<td>'.$item['Name'].'</td>';
                                echo '<td>'.$item['Description'].'</td>';
                                echo '<td>'.$item['Price'].'</td>';
                                echo '<td>'.$item['Add_Date'].'</td>';
                        echo "<td><a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "'class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                                if ($item['Approve'] == 0) {
                                    echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                }
                           
                                
                                echo "</td>";
                                
                                
                            echo '</tr>';
                    }
                    
                    
                    
                    
                    
                    ?>

                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>

       </div>

        <?php }else{
            echo '<div class="container">';
                echo '<div class="nice-message">there i\'s no data here</div>';
                echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>';
            echo '</div>';
       } ?>
       
<!-- SELECT items.*, categories.Name AS category_name ,users.UserName
FROM items 
INNER JOIN categories ON categories.ID = items.Cat_ID 
INNER JOIN users ON users.UserID = items.Member_ID; -->
<?php 
    }elseif($do == 'Add'){  
        ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method='POST'>
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="name" class="form-control" autocomplete="off"  required="required" placeholder="name of the Item" />
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="description" class="form-control" autocomplete="off"  required="required" placeholder="Description of the Item" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="price" class="form-control" autocomplete="off"  required="required" placeholder="price of the Item" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country of made</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="country" class="form-control" autocomplete="off"  required="required" placeholder="Country of made" />
                    </div>
                </div>


                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                                
                                <?php
                                        $allMembers = getAllFrom('*', 'users', "", "", "UserID", $ordering = "DESC");
                                        foreach ($allMembers as $user) {
                                            echo "<option value='".$user['UserID']."'>".$user['UserName']."</option>";
                                        }
                                
                                ?>
                            </select>
                    </div>
                </div>


                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                                
                                <?php
                                        $allCats = getAllFrom('*', 'categories', "where parent = 0", "", "ID", $ordering = "DESC");
                                        foreach ($allCats as $cat) {
                                            echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                            $childCats = getAllFrom('*', 'categories', "where parent = {$cat['ID']}", "", "ID", $ordering = "DESC");
                                            foreach ($childCats as $child) {
                                                echo "<option value='".$child['ID']."'>---".$child['Name']."</option>";
                                            }
                                    
                                        }
                                
                                ?>
                            </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="tags" class="form-control" autocomplete="off"  placeholder="Seperate tags with comma (,)" />
                    </div>
                </div>





                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                                
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class=" col-sm-offset-2  col-sm-10">
                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm "/>
                    </div>
                </div>


            </form>
            
        </div>
        
    <?php
    }elseif($do=='Insert'){
        echo "<h1 class='text-center'>Insert Item</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $price=$_POST["price"];
            $country = $_POST["country"];
            $status = $_POST["status"];
            $cat= $_POST["category"];
            $member=$_POST["member"];
            $tags =$_POST["tags"];

            $formError = array();

            if (empty($name)) {
                $formError[]= "name is required";
            }
            if (empty($desc)) {
                $formError[]= "desc is required";
            }
            if (empty($price)) {
                $formError[]= "price is required";
            }
            if (empty($country)) {
                $formError[]= "country is required";
            }
            if ($status==0) {
                $formError[]= "status is required";
            }
            if ($member==0) {
                $formError[]= "member is required";
            }
            if ($cat==0) {
                $formError[]= "category is required";
            }
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error . '</div>';
                
            }
            if (empty($formError)) {
     
                    $stmt = $conn->prepare("INSERT INTO `items`( `Name`, `Description`, `Price`, `Country_Made`, `Status`, `Add_Date`,`Cat_ID`,`Member_ID`, `tags`) VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags)");
                    $stmt->execute(array(
                        'zname'=>$name,
                        'zdesc'=>$desc,
                        'zprice'=>$price,
                        'zcountry'=>$country,
                        'zstatus'=>$status,
                        'zmember'=>$member,
                        'zcat'=>$cat,
                        'ztags'=>$tags));

                        $theMsg="<div class='alert alert-success'>" .$stmt->rowCount().'record inserted</div>';
                        redirectHome($theMsg);
                    }

                    // ALTER TABLE items ADD CONSTRAINT member_1 FOREIGN KEY (Member_ID)  REFERENCES users(UserID) ON UPDATE CASCADE ON DELETE CASCADE;
         



           
        }else{
            echo "<div class='container'>";
                $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse this page</div>';
                redirectHome($theMsg,'back',6);
            echo "</div>";
        }
        echo "</div>";
    }elseif ($do == 'Edit') {
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;


        $stmt = $conn->prepare("SELECT * FROM `items` WHERE  `item_ID` = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>


        <h1 class="text-center">Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method='POST'>
                <input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                    <input value="<?php echo $item['Name']?>" type="text" name="name" class="form-control" autocomplete="off"  required="required" placeholder="name of the Item" />
                    </div>
                </div>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                    <input value="<?php echo $item['Description']?>" type="text" name="description" class="form-control" autocomplete="off"  required="required" placeholder="Description of the Item" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                    <input value="<?php echo $item['Price']?>" type="text" name="price" class="form-control" autocomplete="off"  required="required" placeholder="price of the Item" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country of made</label>
                    <div class="col-sm-10 col-md-6">
                    <input  value="<?php echo $item['Country_Made']?>"  type="text" name="country" class="form-control" autocomplete="off"  required="required" placeholder="Country of made" />
                    </div>
                </div>


                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                                
                                <?php
                                        $stmt = $conn->prepare("SELECT * FROM `users`");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();
                                        foreach ($users as $user) {
                                            echo "<option value='".$user['UserID']."'";
                                            if ($item['Member_ID'] == $user['UserID']) 
                                            {echo 'selected';} 
                                            echo ">".$user['UserName']."</option>";
                                        }
                                
                                ?>
                            </select>
                    </div>
                </div>


                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                                
                                <?php
                                        $stmt = $conn->prepare("SELECT * FROM `categories`");
                                        $stmt->execute();
                                        $cats = $stmt->fetchAll();
                                

                                        foreach ($cats as $cat) {
                                            echo "<option value='".$cat['ID']."'";
                                            if ($item['Cat_ID'] == $cat['ID']) 
                                            {echo 'selected';} 
                                            echo ">".$cat['Name']."</option>";
                                        }
                                
                                ?>
                            </select>
                    </div>
                </div>






                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                                <option value="0">...</option>
                                <option <?php if ($item['Status'] == 1) {
                                    echo 'selected';} ?> value="1">New</option>
                                <option <?php if ($item['Status'] == 2) {
                                    echo 'selected';} ?> value="2">Like New</option>
                                <option <?php if ($item['Status'] == 3) {
                                    echo 'selected';} ?> value="3">Used</option>
                                <option <?php if ($item['Status'] == 4) {
                                    echo 'selected';} ?> value="4">Very Old</option>
                            </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                    <input value="<?php echo $item['tags']?>"  type="text" name="tags" class="form-control" autocomplete="off"  />
                    </div>
                </div>


                <div class="form-group">
                    <div class=" col-sm-offset-2  col-sm-10">
                    <input type="submit" value="Save Item" class="btn btn-primary btn-sm "/>
                    </div>
                </div>


            </form>
<?php
            $stmt = $conn->prepare("SELECT 
        comments.*, users.UserName AS Member
        FROM 
        comments 
        INNER JOIN 
        users 
        ON 
        users.UserID = comments.user_id
        WHERE item_id=?");
        $stmt->execute(array($itemid));
        $row = $stmt->fetchAll();

        if(!empty($row)){
        
        
        
        ?>
       

       <h1 class="text-center">Mange[<?php echo $item['Name']?>] Comments</h1>
       
        <div class="main-table table-responsive  text-center">
            <table class="table table-bordered" >
                <tr>
                    
                    <td>Comment</td>
             
                    <td>User Name</td>
                    <td>Added Date</td>
                    <td>Control</td>
                </tr>

                <?php
                foreach ($row as $row) {
                        echo '<tr>';
                           
                            echo '<td>'.$row['comment'].'</td>';
                            
                            echo '<td>'.$row['Member'].'</td>';
                            echo '<td>'.$row['comment_date'].'</td>';
                    echo "<td><a href='comments.php?do=Edit&comid=" . $row['c_id'] . "'class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                            
                            if ($row['status'] == 0) {
                                echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                            }
                            
                            echo "</td>";
                            
                            
                        echo '</tr>';
                }
                
                
                
                
                
                ?>

            </table>
           
          

       </div>
       <?php } ?>
        
            
        </div>
    <?php }else{
            echo "<div class='container'>";
            $theMsg= '<div class="alert alert-danger">there is no id</div>';
            redirectHome($theMsg);
            echo "</div>";
            
    }
    }elseif($do == 'Update'){
        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id =$_POST["itemid"];
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $price=$_POST["price"];
            $country = $_POST["country"];
            $status = $_POST["status"];
            $cat= $_POST["category"];
            $member=$_POST["member"];
            $tags=$_POST["tags"];


            // echo $id.$email.$user.$name;

            

            

            $formError = array();

            if (empty($name)) {
                $formError[]= "name is required";
            }
            if (empty($desc)) {
                $formError[]= "desc is required";
            }
            if (empty($price)) {
                $formError[]= "price is required";
            }
            if (empty($country)) {
                $formError[]= "country is required";
            }
            if ($status==0) {
                $formError[]= "status is required";
            }
            if ($member==0) {
                $formError[]= "member is required";
            }
            if ($cat==0) {
                $formError[]= "category is required";
            }
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error . '</div>';
                
            }
            if (empty($formError)) {
               
                
                $stmt = $conn->prepare("UPDATE `items` SET `Name`=?,`Description`=?,`Price`=? , `Country_Made`=? ,`Status`=?,`Cat_ID`=?,`Member_ID`=? ,`tags`=? WHERE `item_ID`=?");
                $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$id,$tags));
            
                
                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record updated</div>';
                    redirectHome($theMsg,'back');
                echo "</div>";
                
            }
        }else{
        
            echo "<div class='container'>";
                $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse this page</div>";
                redirectHome($theMsg,'back');
            echo "</div>";
        }
        echo "</div>";
    }elseif($do=='Delete'){
        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

        

            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
            $check =checkItem('item_ID', 'items', $itemid);
        

            if ($check > 0) { 

                
                $stmt = $conn->prepare("DELETE FROM `items` WHERE `item_ID` =:zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();
                

                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record Deleted</div>';
                    redirectHome($theMsg);
                echo "</div>";
            }else{
                echo "<div class='container'>";
                    $theMsg= 'this id doesnt exist';
                    redirectHome($theMsg,'back');
                echo "</div>";
            }

echo "</div>";
        
    }elseif ($do == 'Approve') {
        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";

        

            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;


            $check =checkItem('item_ID', 'items', $itemid);
   
            if ($check > 0) { 

                
                $stmt = $conn->prepare("UPDATE `items` SET `Approve` =1 WHERE `item_ID`=?");
                $stmt->execute(array($itemid));
                

                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record Updated</div>';
                    redirectHome($theMsg,'back');
                echo "</div>";
            }else{
                echo "<div class='container'>";
                    $theMsg= 'this id doesnt exist';
                    redirectHome($theMsg);
                echo "</div>";
            }

echo "</div>";
    }
    include $tpl.'footer.php';
}else{
    header('location:index.php');
    exit();
}
ob_get_flush();
?>