<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDFunctions
{
	public function toolbar(){
		RMTemplate::get()->add_tool(__('Dashboard','docs'), './index.php', '../images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Resources','docs'), './resources.php', '../images/book.png', 'resources');
		RMTemplate::get()->add_tool(__('Sections','docs'), './sections.php', '../images/section.png', 'sections');
		RMTemplate::get()->add_tool(__('Notes','docs'), './notes.php', '../images/notes.png', 'notes');
		RMTemplate::get()->add_tool(__('Figures','docs'), './figures.php', '../images/figures.png', 'figures');
	}
}
