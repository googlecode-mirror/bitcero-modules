$(document).ready(function(){
    $("a.edit-screen").click(function(){
        var id = $(this).attr("id").replace("edit-",'');
        var params = {
            action: 'get-info',
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            id: id
        };
        $.get('../ajax/upload-screens.php', params, function(data){

            alert(data);

        },'html');
    });
});