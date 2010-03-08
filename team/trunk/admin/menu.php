<?php
// $Id$
// --------------------------------------------------------------
// Equipo Club Gallos
// Un modulo sencillo para el manejo de equipos
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('team', 'admin_');

$adminmenu[0]['title'] = __('Inicio','admin_team');
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = '../images/home.png';
$adminmenu[0]['location'] = 'dashboard';

$adminmenu[1]['title'] = __('Categorías','admin_team');
$adminmenu[1]['link'] = "admin/cats.php";
$adminmenu[1]['icon'] = '../images/cats.png';
$adminmenu[1]['location'] = 'categories';
$adminmenu[1]['options'] = array(
	array('link' => './cats.php', 'title' => __('Lista','admin_team'), 'selected' => 'categories'),
	array('link' => './cats.php?op=new', 'title' => __('Crear categoría','admin_team'), 'selected' => 'newcategory')
);

$adminmenu[2]['title'] = __('Entrenadores','admin_team');
$adminmenu[2]['link'] = "admin/coachs.php";
$adminmenu[2]['icon'] = '../images/coachs.png';
$adminmenu[2]['location'] = 'coachs';
$adminmenu[2]['options'] = array(
	array('link' => './coachs.php', 'title' => __('Lista','admin_team'), 'selected' => 'coachs'),
	array('link' => './coachs.php?op=new', 'title' => __('Crear entrenador','admin_team'), 'selected' => 'newcoach')
);

$adminmenu[3]['title'] = __('Equipos','admin_team');
$adminmenu[3]['link'] = "admin/teams.php";
$adminmenu[3]['icon'] = '../images/teams.png';
$adminmenu[3]['location'] = 'teams';
$adminmenu[3]['options'] = array(
	array('link' => './teams.php', 'title' => __('Lista','admin_team'), 'selected' => 'teams'),
	array('link' => './teams.php?op=new', 'title' => __('Crear equipo','admin_team'), 'selected' => 'newteam')
);

$team = rmc_server_var($_GET,'team','');

$adminmenu[4]['title'] = __('Jugadores','admin_team');
$adminmenu[4]['link'] = "admin/players.php";
$adminmenu[4]['icon'] = '../images/icon24.png';
$adminmenu[4]['location'] = 'players';
$adminmenu[4]['options'] = array(
	array('link' => './players.php?team='.$team, 'title' => __('Jugadores','admin_team'), 'selected' => 'players'),
	array('link' => './players.php?op=new&team='.$team, 'title' => __('Crear jugador','admin_team'), 'selected' => 'players')
);
