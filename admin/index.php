<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';


if(isset($_SESSION['UserName'])){
    header('location:home.php');
}
// print_r($_SESSION);
include 'init.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user"];
    $password = $_POST["pass"];
    $hashpass = sha1($password);

    // echo $hashpass;
    // check if name is taken already
    
        $stmt = $conn->prepare("SELECT `UserName`,`UserID`, `Password` FROM `users` WHERE  `UserName` = ? AND `Password`=? AND `GroupID` = 1 LIMIT 1");
        $stmt->execute(array($username,$hashpass));
        $row = $stmt->fetch(); 
        $count = $stmt->rowCount();
        // echo $count;
        // $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($count>0){
            // echo 'welcome' . $username;
            $_SESSION['UserName']= $username;
            $_SESSION['ID']= $row['UserID'];
            header('location:home.php');
        exit();
        }

        }


?>





    <form class="login" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
        <h3 class="text-center">Admin Login</h3>
        <input class="form-control input-lg" type="text" name="user" placeholder="username" autocomplete="off" />
        <input class="form-control input-lg" type="password" name="pass" placeholder="password" autocomplete="new-password" />
        <input class="btn btn-primary btn-block input-lg" type="submit" value="Login" />
    </form>


<?php
include $tpl.'footer.php';

?>
