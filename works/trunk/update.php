<?php

/**
* Actualización de la Base de datos
*/

include '../../mainfile.php';

$db = Database::getInstance();

$db->queryF("ALTER TABLE `NXlDv_pw_works` ADD `titleid` VARCHAR( 200 ) NOT NULL AFTER `title` ;");
$db->queryF("ALTER TABLE `NXlDv_pw_works` ADD INDEX ( `titleid` ) ;");

echo "Operaciones realizadas";