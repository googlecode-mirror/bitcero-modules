$(document).ready(function(){
    var selector;
    
    editAreaLoader.init({
        id : "editor"		// textarea id
        ,syntax: "css"			// syntax to be uses for highgliting
        ,start_highlight: true		// to display with highlight mode on start-up
    });
    
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