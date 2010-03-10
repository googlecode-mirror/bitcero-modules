<?php
// $Id$
// --------------------------------------------------------------
// EXM System
// Content Management System
// Author: Eduardo Cortés (aka BitC3R0)
// Email: bitc3r0@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

global $rmc_config;

class RMFormDate extends RMFormElement
{
	
	private $_date = 0;
	private $_showtime = 0;

        /**
      * Constructor
      * @param <string> $caption
      * @param <string> $name Nombre identificador del campo
      * @param <string> $date Fecha en formato 'yyyy-mm-14'
      */
	function __construct($caption, $name, $date='', $showtime=0){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_date = $date;
		$this->_showtime = $showtime;
		if (!defined('RM_FRAME_DATETIME_CREATED')) define('RM_FRAME_DATETIME_CREATED',1); // Necesario para incluir el script de fechas
		if (!defined('SCRIPT_PROTOTYPE_INCLUDED')){
			$util =& RMUtilities::get();
		}

                if (defined('EXM_IS_CP') && EXM_IS_CP==true){
                    // This class must be instantiated before that the method ExmGUI::cp_head();
                    RMTemplate::get()->add_script(RMCURL.'/include/js/dates.js');
                }

	}
	
	public function render(){
		global $exmConfig;
                /*$rtn = '';
		$rtn .= '<div class="exmDateField">
                     <div id="txt_'.$this->getName().'" class="exmTextDate">'.($this->_date===null ? _RMS_CFD_SDTXT : date($this->_showtime ? $exmConfig['datestring'] : $exmConfig['dateshort'], $this->_date)).'</div>';
		$rtn .= '<img title="'._RMS_CFD_CLICKTOSEL.'" src="'.ABSURL.'/rmcommon/images/calendar.png" alt="" class="exmfield_date_show_date" onclick="showEXMDates(\''.$this->getName().'\','.$this->_showtime.','.($this->_date===null ? "'time'" : $this->_date).');" style="cursor: pointer;" />';
		$rtn .= '<img title="'._RMS_CFD_CLICKTOCLEAR.'" src="'.ABSURL.'/rmcommon/images/calendardel.png" alt="" onclick="clearEXMDates(\''.$this->getName().'\');" style="cursor: pointer;" />';
		$rtn .= '<input type="hidden" name="'.$this->getName().'" id="'.$this->getName().'" value="'.($this->_date===null ? '' : $this->_date).'" /></div>';
		if (!defined('RM_FRAME_DATETIME_DIVOK')){
			$rtn .= "<div id='exmDatesContainer' class='exmDates'></div>";
			define('RM_FRAME_DATETIME_DIVOK', 1);
		}*/

                $rtn = "\n<script type='text/javascript'>
            \n$(function(){
            \n$(\"#exmdate-".$this->getName()."\").datepicker();
            \n});\n</script>
            \n";
                $rtn .= "<input type='text' class='exmdates_field' name='text_".$this->getName()."' id=\"exmdate-".$this->getName()."\"' size='15' maxlength='10' value='".($this->_date>0 ? date('m/d/Y', $this->_date) : '')."' />
                    <input type='hidden' name='".$this->getName()."' id='".$this->getName()."' value='".($this->_date>0 ? $this->_date : '')."' />";
                if ($this->_showtime){
                    
                }
		return $rtn;
	}
	
}
?>