<?php 
ob_start();
session_start();
$pageTitle = 'Catigories';

if(isset($_SESSION['UserName'])){
    
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        $sort = "ASC";
        $sort_array = array('ASC','DESC');
        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
            $sort = $_GET['sort'];
        }
        $stmt2 = $conn->prepare("SELECT * FROM `categories` WHERE `parent` = 0 ORDER BY `Ordering` $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll();?>


        <h1 class="text-center">Manage Categories</h1>
        <div class="container catigories">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i>Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i>Ordering:[
                        <a class="<?php if ($sort == 'ASC') {
                            echo 'active';}?>" href="?sort=ASC">ASC</a> |
                        <a class="<?php if ($sort == 'DESC') {
                            echo 'active';}?>"  href="?sort=DESC">DESC</a>]
                        <i class="fa fa-eye"></i>View:[
                        <span class="active" data-view="full">Full</span>|
                        <span data-view="classic">Classic</span>]
                    </div>
                </div>
                <div class="panel-body">
                <?php
                    foreach ($cats as $cat) {
                        echo '<div class="cat">';
                            echo '<div class="hidden-buttons">';
                                echo '<a href="catigories.php?do=Edit&catid='.$cat['ID'].'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</a>';
                                echo '<a href="catigories.php?do=Delete&catid='.$cat['ID'].'" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i>Delete</a>';
                            echo '</div>';
                            echo "<h3>".$cat['Name'].'</h3>';
                            echo '<div class="full-view">';
                            echo "<p>";
                            if ($cat['Description'] == '') {
                                echo 'this category has no description';}else{echo $cat['Description'];} echo'</p>';
                                if ($cat['Visibilty']==1) {
                                    echo '<span class="visibilty"><i class="fa fa-eye"></i>Hidden</span>';
                                }
                                    if ($cat['Allow_Comment']==1) {
                                        echo '<span class="commenting"><i class="fa fa-close"></i>Comment disabled</span>';
                                    }
                                    if ($cat['Allow_Ads']==1) {
                                        echo '<span class="adverties"><i class="fa fa-close"></i>Ads is not allowed</span>';
                                    }
                                    $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}"," ", "ID", "ASC");
                                    if (!empty($childCats)) {
                                        echo "<h4 class='child-head'>Child Categories</h4>";
                                        echo "<ul class='list-unstyled child-cats'>";
                                            foreach ($childCats as $c) {
                                                echo '<li class="child-link">
                                                    <a href="catigories.php?do=Edit&catid='.$cat['ID'].'">' . $c['Name'] . '</a>
                                                    <a href="catigories.php?do=Delete&catid='.$cat['ID'].'" class="show-delete confirm">Delete</a>
                                                </li>';
                                            }
                                        echo "</ul>";
                                    }
                            echo '</div>';
                        

                        // get child categories

               
                        echo '</div>';
                        echo "<hr>";



                        }
                    
                    ?>
                </div>
            </div>
            <a href="catigories.php?do=Add" class=" add-category btn btn-xs btn-primary"><i class="fa fa-plus"></i>Add New Category</a>
        </div>




  <?php  }elseif($do == 'Add'){ 
        ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method='POST'>
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="name" class="form-control" autocomplete="off"  required="required" placeholder="name of the Category" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="description" class="form-control"   placeholder="descrip the Category" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                    <input type="text" name="ordering" class="form-control" placeholder="Number the order of the Category" />
                    </div>
                </div>


                	<!-- Start Category Type -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Parent?</label>
						<div class="col-sm-10 col-md-6">
							<select name="parent">
								<option value="0">None</option>
								<?php 
									$allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
									foreach($allCats as $cat) {
										echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!-- End Category Type -->

                <div class="form-group form-group-lg form-group-lg">
                    <label class="col-sm-2 control-label">Visibilty</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="visibilty" id="vis-yes" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visibilty" id="vis-no" value="1" >
                            <label for="vis-no">No</label>
                        </div>
                    
                    </div>
                </div>

                <div class="form-group form-group-lg form-group-lg">
                    <label class="col-sm-2 control-label">Allow_Comment</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="commenting" id="com-yes" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="commenting" id="com-no" value="1" >
                            <label for="vis-no">No</label>
                        </div>
                    
                    </div>
                </div>
              
                <div class="form-group form-group-lg form-group-lg">
                    <label class="col-sm-2 control-label">Allow_Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="ads" id="ads-yes" value="0" checked>
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="ads" id="ads-no" value="1" >
                            <label for="vis-no">No</label>
                        </div>
                    
                    </div>
                </div>

                <div class="form-group">
                    <div class=" col-sm-offset-2  col-sm-10">
                    <input type="submit" value="Add Category" class="btn btn-primary btn-lg "/>
                    </div>
                </div>

            </form>
            
        </div>
        
    <?php
    }elseif($do=='Insert'){
        echo "<h1 class='text-center'>Insert Category</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $order = $_POST["ordering"];
            $visibile = $_POST["visibilty"];
            $comment =$_POST["commenting"];
            $ads = $_POST["ads"];
            $parent = $_POST["parent"];

           


            $check = checkItem('Name','categories',$name);

            if ($check==1) {
                $theMsg= '<div class="alert alert-danger">this Category is exist</div>';
                redirectHome($theMsg, 'back');
            }else {
                
                $stmt = $conn->prepare("INSERT INTO `categories`( `Name`,`Description`,`parent`,`Ordering`, `Visibilty`,`Allow_Comment`,`Allow_Ads`)VALUES(:zname,:zdesc,:zparent,:zorder,:zvisibile,:zcomment,:zads) LIMIT 1");
                $stmt->execute(array(
                    'zname'=>$name,
                    'zads'=>$ads,
                    'zdesc'=>$desc,
                    'zparent'=>$parent,
                    'zorder'=>$order,
                    'zvisibile'=>$visibile,
                    'zcomment'=>$comment
                    ));

                    $theMsg="<div class='alert alert-success'>" .$stmt->rowCount().'record inserted</div>';
                    redirectHome($theMsg,'back',6);
                }

            
         



           
        }else{
            echo "<div class='container'>";
                $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse this page</div>';
                redirectHome($theMsg,'back',6);
            echo "</div>";
        }
        echo "</div>";
    }elseif ($do == 'Edit') {

            $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;
    
    
            $stmt = $conn->prepare("SELECT * FROM `categories` WHERE  `ID` = ? ");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $count = $stmt->rowCount();
    
            if ($count > 0) { ?>
                        <h1 class="text-center">Edit Category</h1>
                                <div class="container">

                                    <form class="form-horizontal" action="?do=Update" method='POST'>
                                        <input type="hidden" name="catid" value="<?php echo $catid;?>"/>
                                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10 col-md-6">
                                            <input type="text" name="name" class="form-control"   required="required" placeholder="name of the Category" value="<?php echo $cat["Name"]?>" />
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label">Description</label>
                                            
                                            <div class="col-sm-10 col-md-6">
                                            <input type="text" name="description" class="form-control"   placeholder="descrip the Category" value="<?php echo $cat["Description"]?>" />
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label">Ordering</label>
                                            <div class="col-sm-10 col-md-6">
                                            <input type="text" name="ordering" class="form-control" placeholder="Number the order of the Category" value="<?php echo $cat["Ordering"]?>" />
                                            </div>
                                        </div>

                                        <!-- Start Category Type -->
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label">Parent?</label>
                                            <div class="col-sm-10 col-md-6">
                                                <select name="parent">
                                                    <option value="0">None</option>
                                                    <?php 
                                                        $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                                                        foreach($allCats as $c) {
                                                            echo "<option value='" . $c['ID'] . "'>" . $c['Name'] . "</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
					                    <!-- End Category Type -->






                                        <div class="form-group form-group-lg form-group-lg">
                                            <label class="col-sm-2 control-label">Visibilty</label>
                                            <div class="col-sm-10 col-md-6">
                                                <div>
                                                    <input type="radio" name="visibilty" id="vis-yes" value="0" <?php if ($cat["Visibilty"] == 0) {echo 'checked';}?>>
                                                    <label for="vis-yes">Yes</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="visibilty" id="vis-no" value="1" <?php if ($cat["Visibilty"] == 1) {echo 'checked';}?> >
                                                    <label for="vis-no">No</label>
                                                </div>
                                            
                                            </div>
                                        </div>

                                        <div class="form-group form-group-lg form-group-lg">
                                            <label class="col-sm-2 control-label">Allow_Comment</label>
                                            <div class="col-sm-10 col-md-6">
                                                <div>
                                                    <input type="radio" name="commenting" id="com-yes" value="0" <?php if ($cat["Allow_Comment"] == 0) {echo 'checked';}?>>
                                                    <label for="vis-yes">Yes</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="commenting" id="com-no" value="1" <?php if ($cat["Allow_Comment"] == 1) {echo 'checked';}?> >
                                                    <label for="vis-no">No</label>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    
                                        <div class="form-group form-group-lg form-group-lg">
                                            <label class="col-sm-2 control-label">Allow_Ads</label>
                                            <div class="col-sm-10 col-md-6">
                                                <div>
                                                    <input type="radio" name="ads" id="ads-yes" value="0" <?php if ($cat["Allow_Ads"] == 0) {echo 'checked';}?> >
                                                    <label for="vis-yes">Yes</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="ads" id="ads-no" value="1" <?php if ($cat["Allow_Ads"] == 1) {echo 'checked';}?>>
                                                    <label for="vis-no">No</label>
                                                </div>
                                            
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class=" col-sm-offset-2  col-sm-10">
                                            <input type="submit" value="Save" class="btn btn-primary btn-lg "/>
                                            </div>
                                        </div>

                                    </form>
                                    
                                </div>




            






             <?php }else{
                echo "<div class='container'>";
                $theMsg= '<div class="alert alert-danger">there is no id</div>';
                redirectHome($theMsg);
                echo "</div>";
                
        }
    
        













    }elseif($do == 'Update'){
        echo "<h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id = $_POST["catid"];
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $order = $_POST["ordering"];
            $visibile = $_POST["visibilty"];
            $comment = $_POST["commenting"];
            $ads = $_POST["ads"];
            $parent = $_POST["parent"];

            // echo $id.$email.$user.$name;

                $stmt = $conn->prepare("UPDATE `categories` SET `Name`=?,`Description`=?,`Ordering`=?,`parent`=?,`Visibilty`=?,`Allow_Comment`=?,`Allow_Ads`=? WHERE `ID`=?");
                $stmt->execute(array($name ,$desc,$order,$parent,$visibile,$comment,$ads,$id));
            
                
                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record updated</div>';
                    redirectHome($theMsg,'back');
                echo "</div>";
                
            
        }else{
        
            echo "<div class='container'>";
                $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse this page</div>";
                redirectHome($theMsg,'back');
            echo "</div>";
        }
        echo "</div>";

    }elseif($do=='Delete'){

        
        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";

        

            $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;


            $check =checkItem('ID', 'categories', $catid);


            if ($check > 0) { 

                
                $stmt = $conn->prepare("DELETE FROM `categories` WHERE `ID` =:zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();
                

                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record Deleted</div>';
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