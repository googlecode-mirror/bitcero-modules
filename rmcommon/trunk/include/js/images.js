$(document).ready(function() {
    $('#files-container').uploadify({
        'uploader': 'include/uploadify.swf',
        'script': 'include/upload.php',
        'folder': '/path/to/uploads-folder',
        'cancelImg': 'images/cancel.png',
        'scriptData': {'action':'upload'},
        'multi': true,
        'fileExt': '*.jpg;*.png;*.gif',
        'fileDesc': 'Imágenes',
        'sizeLimit': 1073741824,
        'queueSizeLimit': 50,
        'buttonText': 'Seleccionar',
        'onComplete': function(event,id,file,response,data){
            alert(event+' '+id+' '+file['name']+' '+response+' '+data);
            return true;
        }
    });
});

function complete(event,id,file,response,data){
    alert(event+' '+id+' '+file+' '+response+' '+data);
    return false;
}