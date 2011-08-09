<?php

function xoops_module_update_works($mod, $pre){
    
    $db = Database::getInstance();
    
    $db->queryF("ALTER TABLE `".$db->prefix("pw_works")."` ADD `modified` INT( 10 ) NOT NULL AFTER `created` ");
    
}
