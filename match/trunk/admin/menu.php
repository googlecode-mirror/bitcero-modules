<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('match','');
include_once XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

$adminmenu[] = array(
    'title'=>__('Dashboard','match'),
    'link'=>"admin/index.php",
    'icon'=>"../images/dashboard.png",
    'location'=>"dashboard"
);

$adminmenu[] = array(
    'title'=>__('Categories','match'),
    'link'=>"admin/categories.php",
    'icon'=>"../images/category.png",
    'location'=>"categories",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'categories.php', 'selected'=>'categories'),
        array('title'=>__('Add Category', 'match'),'link'=>'categories.php?action=new', 'selected'=>'newcategory')
    )
);

$adminmenu[] = array(
    'title'=>__('Championships','match'),
    'link'=>"admin/champ.php",
    'icon'=>"../images/champ.png",
    'location'=>"championships",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'champ.php', 'selected'=>'championships'),
    )
);

$adminmenu[] = array(
    'title'=>__('Fields','match'),
    'link'=>"admin/fields.php",
    'icon'=>"../images/field.png",
    'location'=>"fields",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'fields.php', 'selected'=>'fields')
    )
);

$adminmenu[] = array(
    'title'=>__('Teams','match'),
    'link'=>"admin/teams.php",
    'icon'=>"../images/teams.png",
    'location'=>"teams",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'teams.php', 'selected'=>'teams'),
        array('title'=>__('Add Team', 'match'),'link'=>'teams.php?action=new', 'selected'=>'newteam')
    )
);

$adminmenu[] = array(
    'title'=>__('Players','match'),
    'link'=>"admin/roster.php",
    'icon'=>"../images/players.png",
    'location'=>"roster",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'roster.php', 'selected'=>'roster'),
        array('title'=>__('Add Player', 'match'),'link'=>'roster.php?action=new', 'selected'=>'newplayer')
    )
);

$adminmenu[] = array(
    'title'=>__('Coaches','match'),
    'link'=>"admin/coaches.php",
    'icon'=>"../images/coaches.png",
    'location'=>"coaches",
    'options' => array(
        array('title'=>__('List all', 'match'),'link'=>'coaches.php', 'selected'=>'coaches'),
        array('title'=>__('Add Coach', 'match'),'link'=>'coaches.php?action=new', 'selected'=>'newcoach')
    )
);

$adminmenu[] = array(
    'title'=>__('Role Play','match'),
    'link'=>"admin/role.php",
    'icon'=>"../images/role.png",
    'location'=>"role"
);

$adminmenu[] = array(
    'title'=>__('Ranking','match'),
    'link'=>"admin/ranking.php",
    'icon'=>"../images/rank.png",
    'location'=>"ranking"
);
