$(document).ready(function(){
    var selector;
   
});

function brighten(color, percent) {
    var r=parseInt(color.substr(1,2),16);
    var g=parseInt(color.substr(3,2),16);
    var b=parseInt(color.substr(5,2),16);
    
    return '#'+
       Math.min(255,Math.floor(r*percent)).toString(16)+
       Math.min(255,Math.floor(g*percent)).toString(16)+
       Math.min(255,Math.floor(b*percent)).toString(16);
}