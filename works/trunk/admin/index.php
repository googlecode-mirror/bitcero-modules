<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','index');
include 'header.php';

define('WORKS_LOCATION', 'dashboard');
PWFunctions::toolbar();

// Widgets
$widgets_right = array();
$widgets_left = array();
$widgets_right = RMEvents::get()->run_event('works.dashboard.right.widgets', $widgets_right);
$widgets_left = RMEvents::get()->run_event('works.dashboard.left.widgets', $widgets_left);

//Categorías
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_categos');
list($categories) = $db->fetchRow($db->query($sql));

//Tipos de Cliente
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_types');
list($types) = $db->fetchRow($db->query($sql));

//Clientes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_clients');
list($customers) = $db->fetchRow($db->query($sql));

//Trabajos
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_works');
list($works) = $db->fetchRow($db->query($sql));

// IMages
$sql = "SELECT COUNT(*) FROM ".$db->prefix('pw_images');
list($images) = $db->fetchRow($db->query($sql));

// Works not published
$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=0 ORDER BY id_work DESC LIMIT 0,5";
$result = $db->query($sql);
$works_pending = array();
while($row = $db->fetchArray($result)){
	$work = new PWWork();
	$work->assignVars($row);
	$works_pending[] = array(
		'id'	=> $work->id(),
		'title'	=> $work->title(),
		'desc'	=> $work->descShort(),
		'date'	=> formatTimestamp($work->created(), 'c')
	);
}

xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a>");

RMTemplate::get()->add_style('admin.css', 'works');
RMTemplate::get()->add_style('dashboard.css', 'works');
RMTemplate::get()->set_help('http://redmexico.com.mx/docs/professional-works');
xoops_cp_header();

include RMTemplate::get()->get_template("admin/pw_index.php", 'module', 'works');
xoops_cp_footer();
