<?php

ini_set("display_errors", 'On');
// Report all errors
error_reporting(E_ALL);



include 'admin/connect.php';

$sessionUser = "";
if(isset($_SESSION['user'])){
    $sessionUser= $_SESSION['user'];
}

$tpl = "includes/Templates/";
$lang = "includes/languages/";
$func = "includes/functions/";
$css = "layout/css/";
$js = "layout/js/";

include $func.'function.php';
include $lang.'english.php';
include $tpl.'header.php';





?>