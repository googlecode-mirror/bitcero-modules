<?php
// $Id$
// --------------------------------------------------------------
// Quick Pages
// Create simple pages easily and quickly
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

# INFORMACIÓN DEL MÓDULO
define('_MI_QP_DESC','Módulo para la publicación y administración de páginas individuales');

# MENU DE LA SECCIÓN ADMINISTRATIVA
define('_MI_QP_AMENU1','Estado del Módulo');
define('_MI_QP_AMENU2','Categorías');
define('_MI_QP_AMENU3','Páginas');

/**
 * Configuración del Módulo
 */

# Tipo de Editor
define('_MI_QP_FORM_DHTML', 'DHTML');
define('_MI_QP_FORM_COMPACT', 'Compacto');
define('_MI_QP_FORM_TINY', 'TinyMCE');
define('_MI_QP_FORM_HTMLAREA','TextArea');
define('_MI_QP_FORM_FCK', 'FCKEditor');
define('_MI_QP_CNFEDITOR','Tipo de Editor');

# Manejo de Links
define('_MI_QP_CNFLINKS','Método para el manejo de Enlaces');
define('_MI_QP_CNFLINKS_DESC','El método basado en nombres require del Servidor Apache.');
define('_MI_QP_CNFLINKS1','Estándar de PHP');
define('_MI_QP_CNFLINKS2','Basado en Nombres');

# Fechas
define('_MI_QP_CNFDATE','Formato de Fechas');
define('_MI_QP_CNFHOUR','Formato de Hora');

# Texto parala página inicial
define('_MI_QP_CNFHOMETEXT','Texto para la página inicial');
define('_MI_QP_CNFHOMETEXT_DESC','Este texto se mostrará en la página inicial del módulo para información del usuario');

// Almacenar como archivos
define('_MI_QP_ASFILES','Almacenar páginas como archivos');
define('_MI_QP_ASFILES_DESC','Al activar esta opción Quick Pages almacenará las páginas creadas como un archivo HTML. Esto es útil para aminorar la carga en la base de datos.<br />Los archivos almacenados son actualizados cada vez que la página es editada en la administración del módulo.');

// Paginas relacionadas
define('_MI_QP_SHOWRELATED','Mostrar páginas relacionadas');
define('_MI_QP_SHOWRELATED_DESC','Activa o desactiva el recuadro "Páginas Relaciondas" cuando se ve el contenido de una página específica.');
define('_MI_QP_RELATEDNUM','Número de páginas relacionadas');

// Base path
define('_MI_QP_BASEPATH','Rutas bases para las urls');
define('_MI_QP_BASEPATHD','Las urls formadas por el módulo serán generadas a partir de estas rutas base. Especifica cada ruta separada por "|".');

/**
 * Bloques
 */
define('_MI_QP_BKCATS','Secciones');
define('_MI_QP_BKPAGES','Páginas');

// Subpáginas
define('_MI_QP_SUBINDEX','Página Inicial');
define('_MI_QP_SUBCATS','Categorías');
define('_MI_QP_SUBPAGE','Página');
