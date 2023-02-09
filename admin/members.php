<?php 
session_start();
$pageTitle = 'Members';

if(isset($_SESSION['UserName'])){
    
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

        $query = "";

        if (isset($_GET['page']) && $_GET['page'] =='Pending') {
            $query = 'AND RegStatus=0';
        }

        $stmt = $conn->prepare("SELECT * FROM `users` WHERE  `GroupID`!=1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $row = $stmt->fetchAll();
        


        if (!empty($row)) {
        
        
        
        
        ?>
       

       <h1 class="text-center">Mange Members</h1>
       <div class="container">
            <div class="main-table table-responsive manage-members  text-center">
                <table class="table table-bordered" >
                    <tr>
                        <td>#ID</td>
                        <td>UserName</td>
                        <td>Avatar</td>
                        <td>Email</td>
                        <td>FullName</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($row as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['UserID'].'</td>';
                                echo '<td>'.$row['UserName'].'</td>';
                                echo "<td>";
                                if (empty($row['avatar'])) {
                                    echo 'No Image';
                                } else {
                                    echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                                }
                                echo "</td>";

                                echo '<td>'.$row['Email'].'</td>';
                                echo '<td>'.$row['FullName'].'</td>';
                                echo '<td>'.$row['Date'].'</td>';
                        echo "<td><a href='members.php?do=Edit&userid=" . $row['UserID'] . "'class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                                
                                if ($row['RegStatus'] == 0) {
                                    echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                                }
                                
                                echo "</td>";
                                
                                
                            echo '</tr>';
                    }
                    
                    
                    
                    
                    
                    ?>

                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>

       </div>

       <?php }else{
            echo '<div class="container">';
                echo '<div class="nice-message">there i\'s no data here</div>';
                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
            echo '</div>';
       } ?>
       
        
        
    <?php }elseif($do == 'Add'){ ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method='POST' enctype="multipart/form-data">
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Usename</label>
                    <div class="col-sm-10">
                    <input type="text" name="username" class="form-control" autocomplete="off"  required="required" placeholder="Username to login" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                    <input type="password"  name="password" required="required" class="passwordd form-control" autocomplete="new-password" placeholder="password must be hard and complex"/>
                    <!-- <i class="fa-solid fa-eye fa-2x show-eye"></i> -->
                    <i class="fa-regular fa-eye-slash fa-2x  show-pass"></i>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                    <input type="email" name="email" class="form-control"  required="required" placeholder="Email must be valid" />
                    </div>
                </div>

                <div class="form-group form-group-lg form-group-lg">
                    <label class="col-sm-2 control-label">Fullname</label>
                    <div class="col-sm-10">
                    <input type="text" name="full" class="form-control"  required="required" placeholder="Fullname appear in your profile page" />
                    </div>
                </div>

                <!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Avatar Field -->

                <div class="form-group">
                    <div class=" col-sm-offset-2  col-sm-10">
                    <input type="submit" value="Add Member" class="btn btn-primary btn-lg "/>
                    </div>
                </div>

            </form>
            
        </div>
    <?php }elseif($do=='Insert'){
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){

            
            // Upload Variables

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp	= $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            // List Of Allowed File Typed To Upload

            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get Avatar Extension

			$avatarExtension = strtolower(end(explode('.', $avatarName)));




            $pass = $_POST["password"];
            $user = $_POST["username"];
            $email = $_POST["email"];
            $name = $_POST["full"];

            $hashpass =sha1($_POST["password"]);

            

            


            $formError = array();

            if (strlen($user) < 4 && strlen($user) > 20) {
                $formError[]= 'username cant be less than 4 characters or  more than 20 characters';
            }
            if (empty($user)) {
                $formError[]= "user is required";
            }
            if (empty($pass)) {
                $formError[]= "password is required";
            }
            if (empty($email)) {
                $formError[]= "email is required";
            }
            if (empty($name)) {
                $formError[]= "Name is required";
            }
            if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formError[] = 'This Extension Is Not <strong>Allowed</strong>';
            }

            if (empty($avatarName)) {
                $formError[] = 'Avatar Is <strong>Required</strong>';
            }

            if ($avatarSize > 4194304) {
                $formError[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
            }
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error . '</div>';
                
            }
            if (empty($formError)) {

            
                $avatar = rand(0, 10000000000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);


                $check = checkItem('UserName','users',$user);

                if ($check==1) {
                    $theMsg= '<div class="alert alert-danger">this user is exist</div>';
                    redirectHome($theMsg, 'back');
                }else {
                    
                    $stmt = $conn->prepare("INSERT INTO `users`( `UserName`,`Email`,`FullName`, `Password`,`Date`,`RegStatus`,`avatar`)VALUES(:zuser,:zemail,:zname,:zpass,now(),1,:zavatar) LIMIT 1");
                    $stmt->execute(array(
                        'zuser'=>$user,
                        'zemail'=>$email,
                        'zname'=>$name,
                        'zpass'=>$hashpass,
                        'zavatar'=>$avatar));

                        $theMsg="<div class='alert alert-success'>" .$stmt->rowCount().'record inserted</div>';
                        redirectHome($theMsg,'back',6);
                    }

            }
         



           
        }else{
            echo "<div class='container'>";
                $theMsg= '<div class="alert alert-danger">Sorry You Cant Browse this page</div>';
                redirectHome($theMsg);
            echo "</div>";
        }
        echo "</div>";

    }
    
        elseif ($do == 'Edit') {

        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;


        $stmt = $conn->prepare("SELECT * FROM `users` WHERE  `UserID` = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method='POST'>
                        <input type="hidden" name="userid" value="<?php echo $userid;?>"/>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Usename</label>
                            <div class="col-sm-10">
                            <input type="text" name="username" value="<?php echo $row['UserName'];?>" class="form-control" autocomplete="off"  required="required" />
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                            <input type="hidden" name="oldpassword" class="form-control" value="<?php echo $row['Password'];?>" autocomplete="new-password"/>
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank IF You Dont Want TO Change!"/>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                            <input type="email" value="<?php echo $row['Email'];?>" name="email" class="form-control"  required="required" />
                            </div>
                        </div>

                        <div class="form-group form-group-lg form-group-lg">
                            <label class="col-sm-2 control-label">Fullname</label>
                            <div class="col-sm-10">
                            <input type="text" value="<?php echo $row['FullName'];?>" name="full" class="form-control"  required="required" />
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
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";




        if($_SERVER['REQUEST_METHOD']=='POST'){
            $id = $_POST["userid"];
            $user = $_POST["username"];
            $email = $_POST["email"];
            $name = $_POST["full"];

            // echo $id.$email.$user.$name;

            $pass = empty($_POST["newpassword"])?$_POST["oldpassword"]:sha1( $_POST["newpassword"]);

            $formError = array();

            if (strlen($user) < 4 && strlen($user) > 20) {
                $formError[]= 'username cant be less than 4 characters or  more than 20 characters';
            }
            if (empty($user)) {
                $formError[]= "user is required";
            }
            if (empty($email)) {
                $formError[]= "email is required";
            }
            if (empty($name)) {
                $formError[]= "Name is required";
            }
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error . '</div>';
            }
            if (empty($formError)) {

                
                $stmt2 = $conn->prepare("SELECT * FROM `users` WHERE `UserName` = ? AND `UserID` != ?");
                $stmt2->execute(array($user,$id));
                $count = $stmt2->rowCount();

                if ($count ==1) {
                    echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-danger'>This user is exist</div>";
                        redirectHome($theMsg,'back');
                    echo "</div>";
                }else{
                    $stmt = $conn->prepare("UPDATE `users` SET `UserID`=?,`UserName`=?,`Email`=?,`FullName`=? , `Password`=? WHERE `UserID`=$id LIMIT 1");
                    $stmt->execute(array($id ,$user,$email,$name,$pass));
                
                    
                    echo "<div class='container'>";
                        $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record updated</div>';
                        redirectHome($theMsg,'back');
                    echo "</div>";

                }
            }
        }else{
        
            echo "<div class='container'>";
                $theMsg= "<div class='alert alert-danger'>Sorry You Cant Browse this page</div>";
                redirectHome($theMsg,'back');
            echo "</div>";
        }
        echo "</div>";

    }elseif($do=='Delete'){

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;


       
            $check =checkItem('userid', 'users', $userid);
         
            if ($check > 0) { 

                
                $stmt = $conn->prepare("DELETE FROM `users` WHERE `UserID` =:zuser");
                $stmt->bindParam(":zuser", $userid);
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
        
    }elseif ($do == 'Activate') {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

        

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;


            // $stmt = $conn->prepare("SELECT * FROM `users` WHERE  `UserID` = ? LIMIT 1");
            $check =checkItem('userid', 'users', $userid);
            // $stmt->execute(array($userid));
            // $row = $stmt->fetch();
            // $count = $stmt->rowCount();

            if ($check > 0) { 

                
                $stmt = $conn->prepare("UPDATE `users` SET `RegStatus` =1 WHERE `UserID`=?");
                $stmt->execute(array($userid));
                

                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record Updated</div>';
                    redirectHome($theMsg);
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
?>