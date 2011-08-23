<?php
// $Id: notification.php 52 2007-12-29 19:58:44Z ginis $
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

/**
* @desc Genera los datos para el envio de las notificaciones
* @param string I de la categoría
* @param int Id del elemento
* @param string Id del Evento generado
* @param array Parámetros adicionales
* @return string
*/
function bbNotifications($category,$id,$event,$params=array()){

	
	if ($category=='forum'){
		//Notificación de nuevo tema en foro
		if ($event=='newtopic'){
			$forum=new BBForum($id);
			$info['name']=$forum->name();
			$info['url']=XOOPS_URL."/modules/exmbb/topic.php?id=$params[topic]";
			//$info['desc']=$param['topic'];
			return $info;		
		}
		
		//Notificación de nuevo mensaje en foro
		if ($event=='postforum'){
			$forum=new BBForum($id);
			$info['name']=$forum->name();
			$info['url']=XOOPS_URL."/modules/exmbb/topic.php?pid=$params[post]#p$params[post]";
			//$info['desc']=$param['topic'];
			return $info;		
		}
	}

	//Notificación de nuevo mensaje en tema
	if ($category=='topic'){
		$topic=new BBTopic($id);
		$info['name']=$topic->title();
		$info['url']=XOOPS_URL."/modules/exmbb/topic.php?pid=$params[post]#p$params[post]";
		//$info['desc']=$param['topic'];
		
		return $info;
	}


	//Notificación de mensaje en cualquier foro
	if ($category=='any_forum'){
		$forum=new BBForum($params['forum']);
		$info['name']=$forum->name();
		$info['url']=XOOPS_URL."/modules/exmbb/topic.php?pid=$params[post]#p$params[post]";
		//$info['desc']=$param['topic'];
		return $info;

	}
	
	
	
}
?>
