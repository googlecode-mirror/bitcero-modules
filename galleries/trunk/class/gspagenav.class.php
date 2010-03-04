<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

class GSPageNav extends XoopsPageNav
{

	  private $barClass = 'exmNavBar'; 

		    /**
	    * @desc Constructor
	    * 
	    * @param int $total Número total de elementos (No de páginas)
	    * @param int $itemsxpage Número de elementos que se mostrarán en cada página
	    * @param int $start Página o elemento inicial
	    * @param string $varname Nombre de la variable guia
	    * @param string $args Argumentos adicionales
	    * @param int $mode 0 Fucionará por página, 1 Por elementos
	    */
	    function __construct($total, $items_x_page, $start, $varname='pag', $args='', $mode='0'){
		$this->total = intval($total);
		$this->xpage = intval($items_x_page);
		$this->current = intval($start);
		$this->mode = $mode;
		
		$this->url = XOOPS_URL."/modules/galleries/$args/pag/";
		

		$this->previmg = XOOPS_URL.'/modules/system/images/back.png';
		$this->nextimg = XOOPS_URL.'/modules/system/images/next.png';
		$this->lastimg = XOOPS_URL.'/modules/system/images/last.png';
		$this->firstimg = XOOPS_URL.'/modules/system/images/first.png';
		
	    }


	/**
	 * Crea la nevagación
	 *
	 * @param   integer $offset
         * @param int $img Activa o desactiva imágenes en la barra
	 * @return  string
	**/
	public function renderNav($offset = 4){
        global $tpl, $xoopsTpl;
        
		$tpl->assign('navigationClass', $this->barClass);
        //
		if ( $this->total <= $this->xpage ) {
			return '';
		}
        $tpages = ceil($this->total / $this->xpage);
        
        if ($tpages <= 1) return $ret;
        
        /**
        * Esta variable determina el salto de una página a otra
        * dependiendo del valor de "mode". Si mode = 0 entonces
        * se restará uno al valor de navegación, en caso contrario
        * se restará el número de elementos por página
        */
        $rest = $this->mode ? $this->xpage : 1;
        
        $prev = $this->current - $rest;
       
        $pactual = intval(floor(($this->current + $this->xpage) / $this->xpage));
        
        if ($pactual > 1){
            if ($pactual>$offset && $tpages > $offset+1){
                /**
                * Si la página actual es mayor que 2 y el numero total de 
                * página es mayor que once entonces podemos mostrar la imágen
                * "Primer Página" de lo contario no tiene caso tener este botón
                */
                $tpl->append('navigationPages', array('id'=>'first', 'num'=>1));
            }
            /**
            * Si la página actual es mayor que uno entonces mostramos
            * la imágen "Página Anterior"
            */
            $tpl->append('navigationPages', array('id'=>'previous', 'num'=>($pactual-1)));
        }
        
        // Identificamos la primer página y la última página
        $pstart = $pactual-$offset>0 ? $pactual-$offset+1 : 1;
        $pend = ($pstart + 6)<=$tpages ? ($pstart + 6) : $tpages;
        
        if ($pstart > 3 && $tpages>$offset+1+3){
            $tpl->append('navigationPages', array('id'=>3,'salto'=>1,'num'=>3));
        }
        
        if ($tpages > 0){
            for ($i=$pstart;$i<=$pend;$i++){
                $tpl->append('navigationPages', array('id'=>$i,'num'=>$i));
            }
        }
        
        if ($pend < $tpages-3 && $tpages>11){
            $tpl->append('navigationPages', array('id'=>$tpages-3,'salto'=>2,'num'=>($tpages - 3)));
        }
        
        if ($pactual < $tpages && $tpages > 1){
            $tpl->append('navigationPages', array('id'=>'next', 'num'=>($pactual+1)));
            if ($pactual < $tpages-1 && $tpages > 11){
                $tpl->append('navigationPages', array('id'=>'last', 'num'=>$tpages));
            }
        }
        
        $tpl->assign('navigationTotalPages', $tpages);
        $tpl->assign('navigationNextImage', $this->nextimg);
        $tpl->assign('navigationPrevImage', $this->previmg);
        $tpl->assign('navigationLastImage', $this->lastimg);
        $tpl->assign('navigationFirstImage', $this->firstimg);
		$tpl->assign('navigationParams',$this->url);
		$tpl->assign('navigationCurrentPage',$pactual);
        
        $ret = $tpl->fetch("db:gs_navigation_pages.html");
	$tpl->assign('navigationPages',null);
	$tpl->assign('navigationTotalPages',null);
	$tpl->assign('navigationNextImage',null);
	$tpl->assign('navigationPrevImage',null);
	$tpl->assign('navigationLastImage',null);
	$tpl->assign('navigationFirstImage',null);
	$tpl->assign('navigationParams',null);
	$tpl->assign('navigationCurrentPage',null);
	
	
		return $ret;
	}

}
?>
