<?php
// $Id$
// --------------------------------------------------------------
// EXMBB Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class BBFunctions
{
    /**
    * Shows the menu bar for admin gui
    */
    public function menu_bar(){
        RMTemplate::get()->add_tool(__('Dashboard','exmbb'), './index.php', '../images/dash.png', 'dashboard');
        RMTemplate::get()->add_tool(__('Categories','exmbb'), './categos.php', '../images/categos.png', 'categories');
        RMTemplate::get()->add_tool(__('Forums','exmbb'), './categos.php', '../images/forums.png', 'forums');
        RMTemplate::get()->add_tool(__('Announcements','exmbb'), './announcements.php', '../images/bell.png', 'messages');
        RMTemplate::get()->add_tool(__('Reports','exmbb'), './reports.php', '../images/reports.png', 'reports');
        RMTemplate::get()->add_tool(__('Prune','exmbb'), './prune.php', '../images/prune.png', 'prune');
    }
    
}
