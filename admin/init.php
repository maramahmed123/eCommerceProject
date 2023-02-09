<?php
include 'connect.php';

$tpl = "includes/Templates/";
$lang = "includes/languages/";
$func = "includes/functions/";
$css = "layout/css/";
$js = "layout/js/";

include $func.'function.php';
include $lang.'english.php';
include $tpl.'header.php';



if(!isset($noNavbar)){
    include $tpl. 'navbar.php';

}





?>