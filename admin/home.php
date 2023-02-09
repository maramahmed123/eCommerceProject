<?php
ob_start();
session_start();


if(isset($_SESSION['UserName'])){
    $pageTitle = 'Home';
    // echo "welcome".$_SESSION['UserName'];
    include 'init.php';

    // print_r($_SESSION);
    // start home page
    $numUsers = 5;
    $latestUsers = getLatest("*", "users", "UserID", $numUsers);
    $numItems = 6;
    $latestItems = getLatest("*", "items", "item_ID", $numItems);
    $numComments = 4;



    ?>

    <div class="container home-stats text-center">
        <h1>Home Page</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"><?php echo countItems('UserID', 'users');?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("RegStatus" ,"users",0);?></a></span>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"><?php echo countItems('item_ID', 'items');?></a></span>
                    </div>
                </div>
            </div>
            <!-- ALTER TABLE comments ADD CONSTRAINT items_comments FOREIGN KEY (item_id)  REFERENCES items(item_ID) ON UPDATE CASCADE ON DELETE CASCADE; -->
            <div class="col-md-3">
                <div class="stat st-comments">
                <i class="fa fa-comments"></i>
                <div class="info">
                    Total Comments
                    <span><a href="comments.php"><?php echo countItems('c_id', 'comments');?></a></span>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <i class="fa fa-users"></i>Latest Registerd Users <?php echo $numUsers;?>
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                            if (!empty($latestUsers)) {
                                foreach ($latestUsers as $user) {
                                    echo '<li>';
                                    echo $user['UserName'];
                                    echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                    echo '<i class="fa fa-edit"></i>Edit';
                                    if ($user['RegStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' class='btn btn-info activate pull-right'><i class='fa fa-check'></i>Activate</a>";
                                    }
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';
                                }
                            }else {
                                echo 'ther is no record to show';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i>Latest <?php echo $numItems;?> Items Added
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                    <ul class="list-unstyled latest-users">
                            <?php
                            if (!empty($latestItems)) {
                                
                            
                                foreach ($latestItems as $item) {
                                    echo '<li>' ;
                                        echo $item['Name']; 
                                        echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit';
                                                if ($item['Approve'] == 0) {
                                                    echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' class='btn btn-info activate pull-right'><i class='fa fa-check'></i>Approve</a>";
                                                }
                                            echo '</span>';
                                        echo '</a>';
                                    echo'</li>';
                                }
                            }else {
                                echo 'ther is no record to show';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>






<!-- start latest comment -->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <i class="fa fa-comments-o"></i>Latest <?php echo $numComments;?> Comment
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                    <?php

                        $stmt = $conn->prepare("SELECT 
                        comments.*, users.UserName AS Member
                        FROM 
                        comments 
                        INNER JOIN 
                        users 
                        ON 
                        users.UserID = comments.user_id
                        ORDER BY c_id DESC
                        LIMIT $numComments");
                        $stmt->execute();
                        $comments = $stmt->fetchAll();
                    if (!empty($comments)) {


                        foreach ($comments as $comment) {
                            echo '<div class="box-comment">';
                            echo '<span class="member-n">' . $comment['Member'] . '</span>';
                            echo '<p class="member-c">' . $comment['comment'] . '</p>';
                            echo '</div>';
                        }

                    }else {
                        echo 'there is no record to show';
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end latest comment -->

    </div>



<?php



    // end home page
    include $tpl.'footer.php';
}else{
    header('location:index.php');
    exit();
}
ob_get_flush();
?>