<?php
session_start();
$pageTitle = 'Profile';
include 'init.php';
// echo $sessionUser;
if (isset($_SESSION['user'])) {

    $getUser = $conn->prepare("SELECT * FROM `users` WHERE  `UserName` = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];


    ?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Info</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Name :</span> <?php echo $info['UserName']; ?> <br/>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email :</span> <?php echo $info['Email']; ?> <br/>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>FullName :</span> <?php echo $info['FullName']; ?> <br/>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Date :</span> <?php echo $info['Date']; ?> <br/>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favorite Cat:</span>
                    </li>
                </ul>
                <a class="btn btn-default my-button">Edit Info</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
        <div class="panel-heading">My Ads</div>
        <div class="panel-body">
        <div class="row">
        
                <?php
                $myItems = getAllFrom("*", "items", "where Member_ID = $userid", " ", "item_ID", "ASC");
                if (!empty($myItems)) {
                    echo '<div class="row">';
                        foreach ($myItems as $item) {
                            echo '<div class="col-sm-6 col-md-4">';
                                echo '<div class="thumbnail item-box">';
                                if ($item['Approve']==0) {
                                    echo '<span class="approve-status">Waiting Approval</span>'; 
                                }
                                    echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                    echo '<img class="img-responsive" src="logo.PNG" alt="">';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items.php?itemid='. $item['item_ID'] .'">' . $item['Name'] . '</a></h3>';
                                        echo '<p>' . $item['Description'] . '</p>';
                                        echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    echo '</div>';
                }else{
                    echo 'there is no ads to show ,<a href="newad.php">create new ad</a>';
                }
                ?>
    </div>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest comments</div>
            <div class="panel-body">
                <?php
                   $myComments = getAllFrom("comment", "comments", "where user_ide = $userid", " ", "c_id", "ASC");
                   
                   if (! empty($myComments)) {
                    foreach ($myComments as $comment) {
                        echo '<p>' . $comment['comment'] . '</p>';
                    }

                    
                   }else {
                    echo  '<div class="alert alert-danger">there is no comments here</div>';
                   }
                   
                   
                   ?>

                
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