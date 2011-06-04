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
    private $year_range = '';

        /**
      * Constructor
      * @param <string> $caption
      * @param <string> $name Nombre identificador del campo
      * @param <string> $date Fecha en formato 'yyyy-mm-14'
      * @param string Year range (eg. 2000:2020)
      */
	function __construct($caption, $name, $date='', $year_range=''){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_date = $date;
        $this->year_range = $year_range=='' ? (date('Y',time()) - 15).':'.(date('Y',time()) + 15) : $year_range;
        
        RMTemplate::get()->add_local_script('dates.js', 'rmcommon', 'include');

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
            \n$(\"#exmdate-".$this->getName()."\").datepicker({changeMonth: true,changeYear: true, yearRange: '".$this->year_range."'});
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