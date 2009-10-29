$(document).ready(function() {
    $('#files-container').fileUpload({
        'uploader': 'include/uploader.swf',
        'script': 'images.php',
        'folder': '/path/to/uploads-folder',
        'cancelImg': 'images/cancel.png',
        'scriptData': {'action':'upload'},
        'multi': true,
        'fileExt': '*.jpg;*.png;*.gif',
        'fileDesc': 'Imágenes',
        'sizeLimit': 1073741824,
        'buttonText': 'Seleccionar',
        'displayData': 'percentage',
        'onComplete': function(event,id,file,response,data){
            alert(event+' '+id+' '+file+' '+response+' '+data);
            return false;
        }
    });
});

function complete(event,id,file,response,data){
    alert(event+' '+id+' '+file+' '+response+' '+data);
    return false;
}