<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMTimeFormatter
{
    private $time = 0;
    private $format = '';
    
    /**
    * Initialize this class
    * 
    * @param int Unix timestamp
    * @param string Format for time (e.g. Created on %M% %dn%, %Y%)
    * @return RMTimeFormater
    */
    function __construct($time=0, $format=''){
        $this->time = $time;
        $this->format = $format;
    }
    
    /**
    * Singleton Method
    */
    public function get(){
        static $instance;

        if (!isset($instance)) {
            $instance = new RMTimeFormatter();
        }

        return $instance;
    }
    
    public function format($time=0, $format=''){
        
        $time = $time<=0 ? $this->time : $time;
        $format = $format=='' ? $this->format : $format;
        
        if ($format=='' || $time<=0){
            trigger_error(__('You must provide a valid time and format value to use RMTimeFormatter::format() method','rmcommon'));
            return;
        }
        
        $find = array(
            '%d%', // Day number
            '%D%', // Day name
            '%m%', // Month number
            '%M%', // Month name
            '%y%', // Year with two digits (e.g. 04, 05, etc.)
            '%Y%', // Year with four digits (e.g. 2004, 2005, etc.)
            '%h%', // Hour
            '%i%', // Minute
            '%s%' // Second
        );
        
        $replace = array(
            date('d', $time),
            $this->days($time),
            date('m', $time),
            $this->months($time),
            date('y', $time),
            date('Y', $time),
            date('H', $time),
            date('i', $time),
            date('s', $time)
        );
        
        return str_replace($find, $replace, $format);
        
    }
    
    /**
    * Day name for time formatting
    * 
    * @param int $time
    * @return string Day name
    */
    public function days($time = 0){
        
        $time = $time<=0 ? $this->time : $time;
        if($time<=0) return;
        
        $days = array(
            __('Sunday','rmcommon'),
            __('Monday','rmcommon'),
            __('Tuesday','rmcommon'),
            __('Wednesday','rmcommon'),
            __('Thursday','rmcommon'),
            __('Friday','rmcommon'),
            __('Saturday','rmcommon')
        );
        
        return $days[date("w", $time)];
        
    }
    
    public function months($time=0){
        $time = $time<=0 ? $this->time : $time;
        if($time<=0) return;
        
        $months = array(
            __('January', 'rmcommon'),
            __('February', 'rmcommon'),
            __('March', 'rmcommon'),
            __('April', 'rmcommon'),
            __('May', 'rmcommon'),
            __('June', 'rmcommon'),
            __('July', 'rmcommon'),
            __('August', 'rmcommon'),
            __('September', 'rmcommon'),
            __('October', 'rmcommon'),
            __('November', 'rmcommon'),
            __('December', 'rmcommon'),
        );
        
        return $months[date('n', $time)-1];
        
    }
    
    
}
