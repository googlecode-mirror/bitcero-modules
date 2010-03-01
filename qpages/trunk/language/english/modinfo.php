<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

# INFORMACIóN DEL MóDULO
define('_MI_QP_DESC','Module for publishing and management of single pages.');

# MENU DE LA SECCIÓN ADMINISTRATIVA
define('_MI_QP_AMENU1','Module Status');
define('_MI_QP_AMENU2','Categories');
define('_MI_QP_AMENU3','Pages');

/**
 * Configuración del Módulo
 */

# Tipo de Editor
define('_MI_QP_FORM_DHTML', 'DHTML');
define('_MI_QP_FORM_COMPACT', 'Compact');
define('_MI_QP_FORM_TINY', 'TinyMCE');
define('_MI_QP_FORM_HTMLAREA','TextArea');
define('_MI_QP_FORM_FCK', 'FCKEditor');
define('_MI_QP_CNFEDITOR','Editor Type');

# Manejo de Links
define('_MI_QP_CNFLINKS','Links Management Method.');
define('_MI_QP_CNFLINKS_DESC','The method based on names requires the Apache Server.');
define('_MI_QP_CNFLINKS1','PHP Default');
define('_MI_QP_CNFLINKS2','Based on Names');

# Fechas
define('_MI_QP_CNFDATE','Date Format');
define('_MI_QP_CNFHOUR','Hour Format');

# Texto parala p?gina inicial
define('_MI_QP_CNFHOMETEXT','Home page text');
define('_MI_QP_CNFHOMETEXT_DESC','This text will show in the module home page to inform the user.');

define('_MI_QP_SHOWRELATED','Show related pages');
define('_MI_QP_SHOWRELATED_DESC','Enable the "Related Pages" square when a page is viewed.');
define('_MI_QP_RELATEDNUM','Number of related pages');

// Base path
define('_MI_QP_BASEPATH','Base paths for urls');
define('_MI_QP_BASEPATHD','The urles formed by module will be generated from these base paths. Specify each one seprated by "|"');

/**
 * Bloques
 */
define('_MI_QP_BKCATS','Sections');
define('_MI_QP_BKPAGES','Pages');

// Subpáginas
define('_MI_QP_SUBINDEX','Home Page');
define('_MI_QP_SUBCATS','Categories');
define('_MI_QP_SUBPAGE','Page');
