<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('_MI_AH_NAME','Ability Help');
define('_MI_AH_DESC','Módulo para el manejo de documentación en línea');

define('_MI_AH_INIT','Inicio');
define('_MI_AH_RESOURCES','Publicaciones');
define('_MI_AH_SECTIONS','Secciones');
define('_MI_AH_PAGES','Contenidos');
define('_MI_AH_REFS','Referencias');
define('_MI_AH_FIGURES','Figuras');
define('_MI_AH_EDITS','Modificaciones');
define('_MI_AH_NEW','Crear publicación');

//Imagen
define('_MI_AH_IMAGE','Tamaño de la Imágen');
define('_MI_AH_DESCIMAGE','Especifique el tamño en pixeles de las imágenes');
define('_MI_AH_REDIMIMAGE','Tipo de Redimensión');
define('_MI_AH_CROP','Recortar');
define('_MI_AH_REDIM','Redimensionar');
define('_MI_AH_FILE','Tamaño de archivo de imagen');
define('_MI_AH_DESCSIZE','Tamaño de imagen en Kilobytes');

//Formato de acceso a información
define('_MI_AH_ACCESS','Método para el manejo de URLS');
define('_MI_AH_DESCACCESS','Determina la forma en que Ability Help manejará las URLS para acceder a los recursos.');
define('_MI_AH_PHP','Método por defecto de PHP');
define('_MI_AH_NUMERIC','Modo Numérico');
define('_MI_AH_ALPHA','Basado en Nombres');

define('_MI_AH_BASEPATH','Ruta básica para acceso');
define('_MI_AH_BASEPATHD','Esta ruta especifica la url que se formará para acceder a la sección de documentación. Generalmente se debería dejar tal y como está, sin embargo si usted modifica el archivo htaccess de su sitio puede especificar un directorio diferente. (eg. /docs)');

//Editor de título
define('_MI_AH_TITLE','Título de la Sección');
define('_MI_AH_TITLE_DESC','Título con el que se identificará el módulo en la sección frontal');

//Limite de publicaciones recientes a visualizar en página frontal
define('_MI_AH_PUBLIC','Total de publicaciones en página principal');
define('_MI_AH_DESCPUBLIC','Total de publicaciones recientes a visualizar en página principal');


//Mostrar publicaciones recientes o populares
define('_MI_AH_PUBLICTYPE','Tipo de publicaciones a mostrar');
define('_MI_AH_DESCPUBLICTYPE','Define el tipo de publicación a mostrar. Podrá ser recientes o populares');
define('_MI_AH_RECENT','Recientes');
define('_MI_AH_POPULAR','Populares');
define('_MI_AH_VOTES','Mejor votadas');

//Número de Lecturas Recomendadas a visualizar
define('_MI_AH_RECOMMEND','Total de lecturas destacadas en la página principal');
define('_MI_AH_DESCRECOMMEND','Total de lecturas recomendadas a visualizar en página principal');

// Ancho del indice en la infromación del Recurso
define('_MI_AH_INDEXWIDTH','Ancho del Indice en Pixeles');

// Método par alas referencias
define('_MI_AH_REFSMETHOD','Modo para mostrar referencias');
define('_MI_AH_REFSMETHODBOTTOM','Al pie de la página');
define('_MI_AH_REFSMETHODDIV','Recuadro flotante');

define('_MI_AH_REFSCOLOR','Color para resaltar las referencias');

define('_MI_AH_PRINT','Activar Impresión de Contenido');

define('_MI_AH_CREATERES','Permitir la creación de nuevos recursos');
define('_MI_AH_CREATEGROUPS','Grupos que pueden crear recursos');

//Aprobar automáticamente publicación
define('_MI_AH_APPROVED','Aprobar automáticamente las publicaciones');

//Direccion de correo para notificacion
define('_MI_AH_MAIL','Dirección de Correo');
define('_MI_AH_DESCMAIL','Dirección de correo donde se enviará la notificación de nueva publicación no aprobada');

//Limite de publicaciones en pagina de búsqueda
define('_MI_AH_SEARCH','Total de publicaciones en búsqueda');
define('_MI_AH_DESCSEARCH','Número total de publicaciones a mostrar en la página de búsqueda');

// Home text
define('_MI_AH_HOMETEXT','Texto para la página inicial');
define('_MI_AH_HOMETEXTD','Este texto será mostrado como bienvenida o información para los usuarios que ingresen a la página principal del módulo.');

// Modificaciones
define('_MI_AH_MODLIMIT','Limite para modificaciones recientes en la página principal');

// BLOQUES
define('_MI_AH_BKRES','Publicaciones');
define('_MI_AH_BKRES_DESC','Bloque para mostrar publicaciones');
define('_MI_AH_BKINDEX','Contenido de la Sección');
define('_MI_AH_BKINDEXD','Muestra el indice de contenido para una sección.');


//Páginas del módulo
define('_MI_AH_INDEX','Página Inicial');
define('_MI_AH_RESOURCE','Índice de la Publicación');
define('_MI_AH_CONTENT','Contenido de la Publicación');
define('_MI_AH_EDIT','Editar Publicación');
define('_MI_AH_PUBLISH','Crear Publicación');
define('_MI_AH_PSEARCH','Búsqueda');