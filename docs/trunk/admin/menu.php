<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('docs');

if(RMCLOCATION=='sections'){
    $res = rmc_server_var($_REQUEST,'id',0);
} else {
    $res = rmc_server_var($_REQUEST,'res',0);
}

//Inicio
$adminmenu[0]['title'] = __('Dashboard','docs');
$adminmenu[0]['link'] = "./admin/index.php";
$adminmenu[0]['icon'] = "../images/dashboard.png";
$adminmenu[0]['location'] = "dashboard";

// Home page
$adminmenu[1]['title'] = __('Home Page','docs');
$adminmenu[1]['link'] = "./admin/hpage.php";
$adminmenu[1]['icon'] = "../images/homepage.png";
$adminmenu[1]['location'] = "homepage";

//Publicaciones
$adminmenu[2]['title'] = __('Resources','docs');
$adminmenu[2]['link'] = "./admin/resources.php";
$adminmenu[2]['icon'] = "../images/book.png";
$adminmenu[2]['location'] = "resources";
$adminmenu[2]['options'] = array(
	array('title'=>__('All resources','docs'), 'link'=>'resources.php', 'selected'=>'resources'),
	array('title'=>__('New resource','docs'), 'link'=>'resources.php?action=new', 'selected'=>'newresource'),
	array('title'=>__('Drafts','docs'), 'link'=>'resources.php?action=drafts', 'selected'=>'drafts')
);

//Secciones
$adminmenu[3]['title'] = __('Sections','docs');
$adminmenu[3]['link'] = "./admin/sections.php?id=".$res;
$adminmenu[3]['icon'] = "../images/section.png";
$adminmenu[3]['location'] = "sections";
$adminmenu[3]['options'] = array(
    array('title'=>__('All sections','docs'), 'link'=>'sections.php?id='.$res, 'selected'=>'sections'),
    array('title'=>__('New section','docs'), 'link'=>'sections.php?action=new&amp;id='.$res, 'selected'=>'newsection')
);

//Referencias
$adminmenu[4]['title'] = __('Notes','docs');
$adminmenu[4]['link'] = "./admin/notes.php?res=".$res;
$adminmenu[4]['icon'] = "../images/notes.png";
$adminmenu[4]['location'] = "notes";
$adminmenu[4]['options'] = array(
    array('title'=>__('All notes','docs'), 'link'=>'notes.php?res='.$res, 'selected'=>'notes')
);

//Figuras
$adminmenu[5]['title'] = __('Figures','docs');
$adminmenu[5]['link'] = "./admin/figures.php?res=".$res;
$adminmenu[5]['icon'] = "../images/figures.png";
$adminmenu[5]['location'] = "figures";
$adminmenu[5]['options'] = array(
    array('title'=>__('All figures','docs'), 'link'=>'figures.php?res='.$res, 'selected'=>'figures'),
    array('title'=>__('New figure','docs'), 'link'=>'figures.php?action=new&amp;res='.$res, 'selected'=>'newfigure')
);

//Ediciones
$adminmenu[6]['title'] = __('Waiting','docs');
$adminmenu[6]['link'] = "./admin/edits.php";
$adminmenu[6]['icon'] = "../images/waiting.png";
$adminmenu[6]['location'] = "waiting";

