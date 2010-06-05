<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
load_mod_locale('docs');

//Inicio
$adminmenu[0]['title'] = __('Dashboard','docs');
$adminmenu[0]['link'] = "./admin/index.php";
$adminmenu[0]['icon'] = "../images/dashboard.png";


//Publicaciones
$adminmenu[1]['title'] = __('Resources','docs');
$adminmenu[1]['link'] = "./admin/resources.php";
$adminmenu[1]['icon'] = "../images/book.png";

//Secciones
$adminmenu[2]['title'] = __('Sections','docs');
$adminmenu[2]['link'] = "./admin/sections.php";
$adminmenu[2]['icon'] = "../images/section.png";

//Referencias
$adminmenu[4]['title'] = __('Notes','docs');
$adminmenu[4]['link'] = "./admin/refs.php";
$adminmenu[4]['icon'] = "../images/notes.png";

//Figuras
$adminmenu[5]['title'] = _MI_AH_FIGURES;
$adminmenu[5]['link'] = "./admin/figs.php";
$adminmenu[5]['icon'] = "images/figures.png";

//Ediciones
$adminmenu[6]['title'] = _MI_AH_EDITS;
$adminmenu[6]['link'] = "./admin/edits.php";
$adminmenu[6]['icon'] = "images/edits.png";

?>
