<?php
ob_start();
session_start();
$pageTitle = 'Login';
if(isset($_SESSION['user'])){
    header('location:index.php');
}
include 'init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["login"])) {
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $hashpass = sha1($pass);

        // echo $hashpass;
        // check if name is taken already

        $stmt = $conn->prepare("SELECT `UserID`, `UserName`,`Password` FROM `users` WHERE  `UserName` = ? AND `Password`=?LIMIT 1");
        $stmt->execute(array($user, $hashpass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();


        if ($count > 0) {
            // echo 'welcome' . $username;
            $_SESSION['user'] = $user;
            $_SESSION['uid'] = $get['UserID'];
            header('location:index.php');
            exit();
        }
    }else {
        $formError = array();
        $username 	= $_POST['username'];
        $password 	= $_POST['password'];
        $password2 	= $_POST['password2'];
        $email 		= $_POST['email'];

        if (isset($username)) {
            $filterUser = filter_var($username,FILTER_SANITIZE_STRING);
            
            if (strlen($filterUser) < 4) {
                $formError[]= "user can\'t be less than 4 char";
            }
        }

        if (isset($password) && isset($password2)) {
            if (empty($password)) {
                $formError[]= "Sorry can\'t be empty";
            }
            if (sha1($password) !== sha1($password2)) {
                $formError[]= "Sorry password is not match";
            }
        }

        if (isset($email)) {
            $filterEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
           
            if (filter_var($filterEmail,FILTER_VALIDATE_EMAIL)!=true) {
                $formError[]= "This is not valid";
            }
        }


        if (empty($formError)) {

            // Check If User Exist in Database

            $check = checkItem("Username", "users", $username);

            if ($check == 1) {

                $formError[] = 'Sorry This User Is Exists';

            } else {

                // Insert Userinfo In Database

                $stmt = $conn->prepare("INSERT INTO 
											users(Username, Password, Email, RegStatus, Date)
										VALUES(:zuser, :zpass, :zmail, 0, now())");

                $stmt->execute(array(

                    'zuser' => $username,
                    'zpass' => sha1($password),
                    'zmail' => $email

                ));

                // Echo Success Message

                $succesMsg = 'Congrats You Are Now Registerd User';

            }

        }

    

        
    }
        }
?>
<div class="container login-page">
    <h1 class="text-center">
        <span data-class="login"class="selected">Login</span> | <span data-class="signup">Signup</span>
    </h1>
    <!-- start login form -->
    <form class="login" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <input class="form-control" type="text" placeholder="type your username" name="username" autocomplete="off" />
            <input class="form-control" placeholder="type your password" type="password" name="password" autocomplete="new-password" />
            <input class="btn btn-primary btn-block" type="submit" value="Login" name="login" />
    </form>
    <!-- end login form -->

    <!-- start signup form -->
    <form class="signup" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <input pattern=".{4,}" title="User must be more thab 4 chars" class="form-control" type="text" placeholder="type your username" name="username" autocomplete="off" required />
            <input minlength="4" class="form-control" placeholder="type your password" type="password" name="password" autocomplete="new-password" />
            <input minlength="4" class="form-control" placeholder="type your password again" type="password" name="password2" autocomplete="new-password" />
            <input class="form-control" placeholder="type your email" type="email" name="email"/>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
    </form>
    <!-- end signup form -->

    <div class="the-errors text-center">
    <?php if (!empty($formError)) {
            foreach ($formError as $error) {
                echo '<div class="msg error">' . $error . '</div>';
                
            }
            if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}


    }
?>
    </div>
</div>




<?php
include $tpl.'footer.php';
ob_end_flush();
?>