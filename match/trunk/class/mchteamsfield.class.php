<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MCHTeamsField extends RMFormElement
{
    /**
    * Initial selected team
    */
    private $selected = 0;
    
    public function __construct($caption, $name, $selected=0){
        $this->setName($name);
        $this->setCaption($caption);
        $this->selected = $selected;
        
        RMTemplate::get()->add_local_script('teams_field.js', 'match');
    }
    
    /**
    * Create field code
    */
    public function render(){
        
        if($this->selected>0){
            $team = new MCHTeam($this->selected);
        }
        
        $rtn = '<div class="mch_teams_field" id="teams-container-'.$this->getName().'" title="'.__('Click to select a new team','match').'">';
        
        if($this->selected>0 && !$team->isNew()){
            $tf = new RMTimeFormatter(0, __('%M% %d%, %Y%','match'));
            $rtn .= '<img src="'.MCH_UP_URL.'/'.$team->getVar('logo').'" class="logo" alt="'.$team->getVar('name').'" />';
            $rtn .= '<strong>'.$team->getVar('name').'</strong>';
            $rtn .= '<span>'.$tf->format($team->getVar('created')).'</span>';
        } else {
            
            $rtn .= '<img src="'.MCH_URL.'/images/add.png" alt="'.__('Add team','match').'" /><span class="noselected">'.__('Select one team...','match').'</span>';
            
        }
        $rtn .= '</div><div id="mch-teams-loader-'.$this->getName().'" class="mch_teams_pop"></div>';
        $rtn .= '<input type="hidden" name="'.$this->getName().'" id="mch-tf-value-'.$this->getName().'" value="'.($this->selected>0 && !$team->isNew() ? $this->selected : '').'" />';
        
        return $rtn;
        
    }
}
