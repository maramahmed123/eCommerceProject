<?php


function lang($phrase){
    static $lang = array(
        'NO_PHOTO' => 'No photo\'s available',
        'NEW_MEMBER' => 'This user is new',
        'HOME_ADMIN'=>'Home',
        'ITEMS'=>'Items',
        'CATEGORIES'=>'Categories',
        'LOGS'=>'logs',
        'STATISTICS'=>'statistics',
        'MEMEBRS'=>'Members',
        'COMMENTS'=>'Comments',
    );
    return $lang[$phrase];
}




?>