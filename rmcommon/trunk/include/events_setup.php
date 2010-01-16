<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$rmc_events = array(
    'rmcevent_load_right_widgets',
    'rmcevent_load_left_widgets',
    'rmevent_get_locale',
    'rmevent_tinymce_plugin_loading',
    'rmevent_tinymce_plugin_functions',
    'rmcevent_format_code',
    'rmcevent_get_emotions',
    'rmcevent_clean_url',
    'rmcevent_get_replace_patterns',
    'rmcevent_text_todisplay',
    'rmevent_admin_output',
    'rm_loading_editorimages',				// Antes de cargar la plantilla para mostrar el administrador de imagenes en editores
    'rm_imgmgr_editor_options',
    'rm_loading_single_editorimgs',      // Se cargan las imágenes en tiny-images.php
    'rmc_page_loaded',           // Called in footer.php file just before to show the page
    'rm_comments_form',             // Called when comments form is loading
    'rm_comment_saved',             // Called inmediatly after save a comment
);

$rmc_methods['rmc_include_styles'] = array(
    'event'    =>    'rmc_page_loaded',
    'file'    =>    '/modules/rmcommon/include/events-methods.php'
);

