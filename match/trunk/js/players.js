// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
   
   $("#team").autocomplete('../include/teams.php', {formatResult: function(item){
       var pos = item[0].indexOf("<em>");
       return item[0].substr(0,pos-1);
   }});
    
});