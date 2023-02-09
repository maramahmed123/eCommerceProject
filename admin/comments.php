<?php 
session_start();
$pageTitle = 'Comments';

if(isset($_SESSION['UserName'])){
    
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

        $stmt = $conn->prepare("SELECT 
        comments.*, items.Name AS Item_Name , users.UserName AS Member
        FROM 
        comments 
        INNER JOIN 
        items 
        ON 
        items.item_ID = comments.item_id 
        INNER JOIN 
        users 
        ON 
        users.UserID = comments.user_id
        ORDER BY c_id DESC");
        $stmt->execute();
        $comments = $stmt->fetchAll();

        if (!empty($comments)) {
            
        
        
        
        
        ?>
       

       <h1 class="text-center">Mange Comments</h1>
       <div class="container">
            <div class="main-table table-responsive  text-center">
                <table class="table table-bordered" >
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($comments as $comment) {
                            echo '<tr>';
                                echo '<td>'.$comment['c_id'].'</td>';
                                echo '<td>'.$comment['comment'].'</td>';
                                echo '<td>'.$comment['Item_Name'].'</td>';
                                echo '<td>'.$comment['Member'].'</td>';
                                echo '<td>'.$comment['comment_date'].'</td>';
                        echo "<td><a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "'class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                <a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                                
                                if ($comment['status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid=" . $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                }
                                
                                echo "</td>";
                                
                                
                            echo '</tr>';
                    }
                    
                    
                    
                    
                    
                    ?>

                </table>
            </div>
          

       </div>
       <?php }else{
            echo '<div class="container">';
                echo '<div class="nice-message">there i\'s no data here</div>';
                // echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
            echo '</div>';
       } ?>
       
        
        
    <?php }
    
        elseif ($do == 'Edit') {

        $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;


        $stmt = $conn->prepare("SELECT * FROM `comments` WHERE  `c_id` = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method='POST'>
                        <input type="hidden" name="comid" value="<?php echo $comid;?>"/>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'];?></textarea>
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
            $comid = $_POST["comid"];
            $comment = $_POST["comment"];

                $stmt = $conn->prepare("UPDATE `comments` SET `comment`=? WHERE `c_id`=$comid");
                $stmt->execute(array($comid ,$comment));
            
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

        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";

        

            $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;



            $check =checkItem('c_id', 'comments', $comid);


            if ($check > 0) { 

                
                $stmt = $conn->prepare("DELETE FROM `comments` WHERE `c_id` =:zid");
                $stmt->bindParam(":zid", $comid);
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
        
    }elseif ($do == 'Approve') {
        echo "<h1 class='text-center'>Approve Member</h1>";
        echo "<div class='container'>";

        

            $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;

            $check =checkItem('c_id', 'comments', $comid);

            if ($check > 0) { 

                
                $stmt = $conn->prepare("UPDATE `comments` SET `status` =1 WHERE `c_id`=?");
                $stmt->execute(array($comid));
                

                echo "<div class='container'>";
                    $theMsg= "<div class='alert alert-success'>" .$stmt->rowCount().'record Approved</div>';
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
?>