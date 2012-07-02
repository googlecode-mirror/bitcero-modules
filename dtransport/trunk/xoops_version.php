<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once 'include/xv-header.php';

$modversion['name'] = "D-Transport";
$modversion['description'] = __('Module for create a donwloads section in XOOPS','dtransport');
$modversion['version'] = "2.0";
$modversion['rmversion'] = array('number'=>2,'revision'=>0,'status'=>-2,'name'=>'D-Transport');
$modversion['icon32'] = 'images/icon32.png';
$modversion['icon24'] = 'images/icon24.png';
$modversion['icon48'] = "images/logo.png";
$modversion['icon16'] = "images/icon16.png";
$modversion['author'] = "BitC3R0";
$modversion['authormail'] = "bitc3r0@gmail.com";
$modversion['authorweb'] = "Red México";
$modversion['authorurl'] = "http://www.redmexico.com.mx";
$modversion['url'] = 'www.exmsystem.com';
$modversion['credits'] = "Red México";
$modversion['help'] = "http://www.exmsystem.com/docs/d-transport/";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "dtransport";
$modversion['updatable'] = 1;
$modversion['updateurl'] = 'http://redmexico.com.mx/modules/vcontrol/check.php?id=4';

// Social links
$modversion['social'][0] = array('title' => __('Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/');
$modversion['social'][1] = array('title' => __('LinkedIn', 'rmcommon'),'type' => 'linkedin','url' => 'http://www.linkedin.com/bitcero/');
$modversion['social'][2] = array('title' => __('Red México on Twitter', 'rmcommon'),'type' => 'twitter','url' => 'http://www.twitter.com/redmexico/');
$modversion['social'][3] = array('title' => __('Red México on Facebook', 'rmcommon'),'type' => 'facebook','url' => 'http://www.facebook.com/redmexico/');

// Administración
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Sección Frontal
$modversion['hasMain'] = 1;
// Envio de Descargas
global $xoopsModuleConfig, $xoopsModule, $xoopsUser;
if (isset($xoopsModule) && $xoopsModule->dirname()=='dtransport'){
	$mc =& $xoopsModuleConfig;
	if ($mc['send_download']){
		
		$showsend = false;
		$showsend = in_array(0, $mc['groups_send']);
		$showsend = $showsend ? true : ($xoopsUser ? ($xoopsUser->isAdmin()) : false);
		if (!$showsend && $xoopsUser){
			foreach ($xoopsUser->getGroups() as $k){
				if (in_array($k, $mc['groups_send'])){
					$showsend = true;
				}
			}
		}
		
		if ($showsend){
			$modversion['sub'][0]['name'] = _MI_DT_SEND;
			$modversion['sub'][0]['url'] = 'submit.php';
		}
		
	}
}

//Base de datos
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'dtrans_software';
$modversion['tables'][1] = 'dtrans_software_edited';
$modversion['tables'][2] = 'dtrans_platforms';
$modversion['tables'][3] = 'dtrans_licences';
$modversion['tables'][4] = 'dtrans_votedata';
$modversion['tables'][5] = 'dtrans_downs';
$modversion['tables'][6] = 'dtrans_tags';
$modversion['tables'][7] = 'dtrans_softtag';
$modversion['tables'][8] = 'dtrans_groups';
$modversion['tables'][9] = 'dtrans_logs';
$modversion['tables'][10] = 'dtrans_files';
$modversion['tables'][11] = 'dtrans_screens';
$modversion['tables'][12] = 'dtrans_features';
$modversion['tables'][13] = 'dtrans_alerts';
$modversion['tables'][14] = 'dtrans_licsoft';
$modversion['tables'][15] = 'dtrans_platsoft';
$modversion['tables'][16] = 'dtrans_categos';

// Front Section Templates
$modversion['templates'][] = array('file' => 'dtrans_index.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_header.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_listitem.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_category.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_item.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_itemscreens.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_itemdetails.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_features.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_logs.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_comments.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_download.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_search.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_tags.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_submit.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_mydownloads.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_screens.html','description' => '');
$modversion['templates'][] = array('file' => 'dtrans_files.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_createlogs.html', 'description' => '');
$modversion['templates'][] = array('file' => 'dtrans_createfeatures.html', 'description' => '');

// Permalinks
$modversion['config'][] = array(
    'name' => 'permalinks',
    'title' => '_MI_DT_PERMALINK',
    'description' => '',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array('_MI_DT_MODEDEF' => 0, '_MI_DT_MODESHORT' => 1)
);
$modversion['config'][] = array(
    'name' => 'htbase',
    'title' => '_MI_DT_HTBASE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '/modules/dtransport'
);

//Título
$modversion['config'][] = array(
    'name' => 'title',
    'title' => '_MI_DT_TITLE',
    'description' => '_MI_DT_DESCTITLE',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'size' => 50,
    'default' => ''
);

//Permitir envío de descargas
$modversion['config'][] = array(
    'name' => 'send_download',
    'title' => '_MI_DT_SENDDOWN',
    'description' => '_MI_DT_DESCSENDDOWN',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Grupos que pueden enviar descargas
$modversion['config'][] = array(
    'name' => 'groups_send',
    'title' => '_MI_DT_GROUPS_SEND',
    'description' => '',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => array(XOOPS_GROUP_ADMIN,XOOPS_GROUP_USERS)
);

//Grupos que serán notificados de descargas enviadas
$modversion['config'][] = array(
    'name' => 'groups_notif',
    'title' => '_MI_DT_GROUPS_NOTIF',
    'description' => '',
    'formtype' => 'group_multi',
    'valuetype' => 'array',
    'default' => array(XOOPS_GROUP_ADMIN)
);


//Aprobar descargas enviadas por usuarios registrados
$modversion['config'][] = array(
    'name' => 'approve_register',
    'title' => '_MI_DT_APPREG',
    'description' => '_MI_DT_DESCAPPREG',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Aprobar descargas enviadas por usuarios anónimos
$modversion['config'][] = array(
    'name' => 'approve_anonymous',
    'title' => '_MI_DT_APPANONIM',
    'description' => '_MI_DT_DESCAPPANONIM',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Número máximo de pantallas
$modversion['config'][] = array(
    'name' => 'limit_screen',
    'title' => '_MI_DT_LIMITSCREEN',
    'description' => '_MI_DT_DESCLIMITSCREEN',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'size' => 10,
    'default' => 10
);

//Directorio de descarga segura
$modversion['config'][] = array(
    'name' => 'directory_secure',
    'title' => '_MI_DT_DIRSECURE',
    'description' => '_MI_DT_DESCDIRSECURE',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'size' => 50,
    'default' => ''
);

//Directorio de descarga no segura
$modversion['config'][] = array(
    'name' => 'directory_insecure',
    'title' => '_MI_DT_DIRINSECURE',
    'description' => '_MI_DT_DESCDIRINSECURE',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'size' => 50,
    'default' => XOOPS_UPLOAD_PATH.'/downloads'
);

//Votaciones de usuarios anónimos
$modversion['config'][] = array(
    'name' => 'vote_anonymous',
    'title' => '_MI_DT_VOTEANONIM',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Descargas destacadas
$modversion['config'][] = array(
    'name' => 'dest_download',
    'title' => '_MI_DT_DESTDOWN',
    'description' => '_MI_DT_DESCDESTDOWN',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

// Descargas destacadas en Categorías
$modversion['config'][] = array(
    'name' => 'featured_categos',
    'title' => '_MI_DT_FEATCAT',
    'description' => '_MI_DT_FEATCATDESC',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Limite de descargas destacadas
$modversion['config'][] = array(
    'name' => 'limit_destdown',
    'title' => '_MI_DT_LIMITDEST',
    'description' => '_MI_DT_DESCLIMITDEST',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'size' => 10,
    'default' => 10
);

//Modo para descargas destacadas
$modversion['config'][] = array(
    'name' => 'mode_download',
    'title' => '_MI_DT_MODEDOWN',
    'description' => '_MI_DT_DESCMODEDOWN',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array(_MI_DT_RECENT=>0,_MI_DT_RANDOM=>1)
);

//Activar descargas del dia
$modversion['config'][] = array(
    'name' => 'daydownload',
    'title' => '_MI_DT_ACTIVEDOWN',
    'description' => '_MI_DT_DESCACTIVEDOWN',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Activar descargas del dia en categorías
$modversion['config'][] = array(
    'name' => 'dadydowncat',
    'title' => '_MI_DT_DAYDOWNCAT',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Limite de descargas del dia
$modversion['config'][] = array(
    'name' => 'limit_daydownload',
    'title' => '_MI_DT_LIMITDOWN',
    'description' => '_MI_DT_DESCLIMITDOWN',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'size' => 10,
    'default' => 10
);

//Activar notificadciones
$modversion['config'][] = array(
    'name' => 'active_notify',
    'title' => '_MI_DT_ACTIVENOT',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//imagen miniatura
$modversion['config'][] = array(
    'name' => 'size_ths',
    'description' => '_MI_DT_DESCTHS',
    'size' => 10,
    'title' => '_MI_DT_THS',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '150:150:crop'
);

//Imagen grande
$modversion['config'][] = array(
    'name' => 'size_image',
    'description' => '_MI_DT_DESCIMAGE',
    'size' => 10,
    'title' => '_MI_DT_IMAGE',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => '700:450:resize'
);

//Tipo de redimension
$modversion['config'][] = array(
    'name' => 'redim_image',
    'description' => '',
    'size' => 10,
    'title' => '_MI_DT_REDIMIMAGE',
    'formtype' => 'select',
    'valuetype' => 'int',
    'default' => 0,
    'options' => array('_MI_DT_CROPTHS'=>0,'_MI_DT_CROPBIG'=>1,'_MI_DT_CROPBOTH'=>2,'_MI_DT_REDIM'=>3)
);

//Tamaño del archivo de imagen
$modversion['config'][] = array(
    'name' => 'image',
    'description' => '_MI_DT_DESCSIZE',
    'size' => 10,
    'title' => '_MI_DT_FILE',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 200
);

//Tamaño de archivo de descarga
$modversion['config'][] = array(
    'name' => 'size_file',
    'description' => '_MI_DT_DESCSIZEFILE',
    'size' => 10,
    'title' => '_MI_DT_SIZEFILE',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 1024
);

//Tipo de archivo de descarga
$modversion['config'][] = array(
    'name' => 'type_file',
    'description' => '_MI_DT_DESCTYPEFILE',
    'title' => '_MI_DT_TYPEFILE',
    'formtype' => 'textarea',
    'valuetype' => 'array',
    'default' => 'zip|tar|gz|gif|jpg|png|rar'
);

//Aprobar ediciones
$modversion['config'][] = array(
    'name' => 'aprove_edit',
    'title' => '_MI_DT_APPROVEEDIT',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Notificar cuando se realicen ediciones
$modversion['config'][] = array(
    'name' => 'edit_notif',
    'title' => '_MI_DT_EDIT_NOTIF',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

// Manejo de URLS
$modversion['config'][] = array(
    'name' => 'urlmode',
    'title' => '_MI_DT_URLMODE',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 0
);

//Limite de descargas recientes
$modversion['config'][] = array(
    'name' => 'limit_recents',
    'description' => '',
    'size' => 10,
    'title' => '_MI_DT_LIMITRECENT',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10
);

//Dias para considerar elemento como nuevo
$modversion['config'][] = array(
    'name' => 'new',
    'description' => '_MI_DT_DESCNEWFEAT',
    'size' => 10,
    'title' => '_MI_DT_NEWFEAT',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5
);

// Dias para ocnsiderar un elemento como actualizado
$modversion['config'][] = array(
    'name' => 'update',
    'description' => '_MI_DT_DESCUPDITEM',
    'size' => 10,
    'title' => '_MI_DT_UPDITEM',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5
);

// Dias para ocnsiderar un elemento como actualizado
$modversion['config'][] = array(
    'name' => 'showcats',
    'title' => '_MI_DT_SHOWCATS',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

// Descargas por Página
$modversion['config'][] = array(
    'name' => 'xpage',
    'title' => '_MI_DT_XPAGE',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10,
    'size' => 5
);

// Tamaño de Screenshot miniatura
$modversion['config'][] = array(
    'name' => 'thscreen',
    'title' => '_MI_DT_SCREENTH',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 100,
    'size' => 5
);

// Tamaño de Screenshot normal
$modversion['config'][] = array(
    'name' => 'nscreen',
    'title' => '_MI_DT_SCREENN',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 400,
    'size' => 5
);

//Mostrar Etiquetas
$modversion['config'][] = array(
    'name' => 'active_tags',
    'title' => '_MI_DT_ACTIVETAGS',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

//Limite de Etiquetas
$modversion['config'][] = array(
    'name' => 'limit_tags',
    'title' => '_MI_DT_TOTAL_TAGS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 20,
    'size' => 5
);

//Mostrar programas relacionados
$modversion['config'][] = array(
    'name' => 'active_relatsoft',
    'title' => '_MI_DT_ACTRELATSW',
    'description' => '',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

//Limite de programas relacionados a mostrar
$modversion['config'][] = array(
    'name' => 'limit_relatsoft',
    'title' => '_MI_DT_LIMITRELATSOFT',
    'description' => '_MI_DT_DESC_LIMITRELATSOFT',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5,
    'size' => 5
);

//Mostrar Otros programas de la categoría
$modversion['config'][] = array(
    'name' => 'active_othersw',
    'title' => '_MI_DT_ACTOTHERSW',
    'description' => '_MI_DT_DESCOTHERSW',
    'formtype' => 'yesno',
    'valuetype' => 'int',
    'default' => 1
);

//Limite de otros programas
$modversion['config'][] = array(
    'name' => 'limit_othersw',
    'title' => '_MI_DT_LIMITOTHERSW',
    'description' => '_MI_DT_DESC_LIMITOTHERSW',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 5,
    'size' => 5
);

//Tamaño máximo de fuente para etiquetas
$modversion['config'][] = array(
    'name' => 'size_fonttags',
    'title' => '_MI_DT_SIZEFONTTAGS',
    'description' => '_MI_DT_DESCSIZEFONTTAGS',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 30,
    'size' => 5
);

//Limite de Etiquetas Populares
$modversion['config'][] = array(
    'name' => 'limit_tagspopular',
    'title' => '_MI_DT_TOTAL_TAGSPOP',
    'description' => '_MI_DT_DESC_TOTAL_TAGSPOP',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 100,
    'size' => 5
);

//Número mínimo de caracteres para una etiqueta
$modversion['config'][] = array(
    'name' => 'caracter_tags',
    'title' => '_MI_DT_CARACTER_TAGS',
    'description' => '',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 3,
    'size' => 5
);

//Horas de comprobación de alertas
$modversion['config'][] = array(
    'name' => 'hrs_alerts',
    'title' => '_MI_DT_HRSALERTS',
    'description' => '_MI_DT_DESCHRSALERTS',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 24,
    'size' => 5
);

// Comentarios
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'item.php';
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['extraParams']=array();

// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment.func.php';
$modversion['comments']['callback']['approve'] = 'dt_com_approve';
$modversion['comments']['callback']['update'] = 'dt_com_update';

/** BLOQUES DEL MÓDULO **/

// Bloques Lista de Descargas
$modversion['blocks'][] = array(
    'file' => "dtrasn_bk_items.php",
    'name' => __('Downloads list','dtransport'),
    'description' => __('This block show the downloads list','dtransport'),
    'show_func' => "dt_block_items",
    'edit_func' => "dt_block_items_edit",
    'template' => 'dtrans_bk_items.html',
    'options' => array(0, 10, 0, 0, 0, 0, 0, 0, 0, 0)
);

// Búsquedas Populares
$modversion['blocks'][] = array(
    'file' => "dtrasn_bk_tags.php",
    'name' => __('Popular searches','dtransport'),
    'description' => __('Show a list of downloads for popular searches','dtransport'),
    'show_func' => "dt_block_tags",
    'edit_func' => "dt_block_tags_edit",
    'template' => 'dtrans_bk_tags.html',
    'options' => array(100, 30, 1, 10, "Arial, Helvetica, sans-serif")
);

// Busqueda
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "dtransSearch";

//Páginas del módulo
$modversion['subpages']['index'] =  __('Home Page','dtransport');
$modversion['subpages']['category'] = __('Categories','dtransport');
$modversion['subpages']['features'] = __('Features','dtransport');
$modversion['subpages']['files'] = __('Files','dtransport');
$modversion['subpages']['item'] = __('Item details','dtransport');
$modversion['subpages']['logs'] = __('Logs','dtransport');
$modversion['subpages']['mydowns'] = __('My Downloads','dtransport');
$modversion['subpages']['screens'] = __('Screenshots','dtransport');
$modversion['subpages']['download'] = __('Download file','dtransport');
$modversion['subpages']['tags'] = __('Tags','dtransport');
$modversion['subpages']['submit'] = __('Submit download','dtransport');
$modversion['subpages']['search'] = __('Search','dtransport');
$modversion['subpages']['comments'] = __('Comments','dtransport');
