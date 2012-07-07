$(document).ready(function(){
    $("#table-screens").on('click','a.edit-screen', function(){

        $("#editor").slideUp('fast', function(){
            $("#screen-"+$(this).find("#scr-id").val()).slideDown('fast');
            $(this).remove();
        });

        var id = $(this).attr("id").replace("edit-",'');
        var params = {
            action: 'get-info',
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            id: id
        };
        $.get('../ajax/upload-screens.php', params, function(data){

            if(data.error==1){
                alert(data.message);
                if(data.token=='')
                    window.location.reload();

                $("#XOOPS_TOKEN_REQUEST").val(data.token);
                return;
            }

            var html = '<tr id="editor" style="display: none;">';
            html += '<td colspan="2" align="center"><img src="'+$("#screen-"+id+" img").attr("src")+'" style="width: auto; height: auto;" /></td>'
            html += '<td colspan="2"><div class="dt_table"><div class="dt_row">';
            html += '<div class="dt_cell"><strong>'+jsLang.titleField+'</strong></div>';
            html += '<div class="dt_cell"><input type="text" name="title" id="the-title" value="'+data.title+'" /></div></div>';
            html += '<div class="dt_row">';
            html += '<div class="dt_cell"><strong>'+jsLang.descField+'</strong></div>';
            html += '<div class="dt_cell"><textarea name="desc" id="the-desc" rows="4" cols="40">'+data.description+'</textarea></div></div>'
            html += '<div class="dt_row">';
            html += '<div class="dt_cell">&nbsp;</div>';
            html += '<div class="dt_cell"><input type="button" class="buttonOrange" id="save-data" value="'+jsLang.saveData+'" />';
            html += '<input type="button" id="cancel-save" value="'+jsLang.cancel+'" /><input type="hidden" name="id" id="scr-id" value="'+data.id+'" /></div></div>';
            html += '</div></td></tr>';

            $("#screen-"+id).after(html);

            $("#screen-"+id).slideUp('slow', function(){
                $("#editor").slideDown('fast');
            });

            $("#XOOPS_TOKEN_REQUEST").val(data.token);

        },'json');

        return false;
    });

    $("#table-screens").on('click','a.delete-screen', function(){

        var id = $(this).attr("id").replace("delete-",'');

        if(!confirm(jsLang.deleteScreen))
            return false;

        var params = {
            action: 'delete-screen',
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            id: id
        };

        $.post('../ajax/upload-screens.php', params, function(data){

            if(data.error==1){
                alert(data.message);
                if(data.token=='')
                    window.location.reload();

                $("#XOOPS_TOKEN_REQUEST").val(data.token);
                return false;
            }

            $("#screen-"+data.id).slideUp('fast', function(){$(this).remove();});
            $("#XOOPS_TOKEN_REQUEST").val(data.token);

        },'json');

        return false;

    });

    $("#table-screens").on("click", '#cancel-save', function(){
        $("#editor").slideUp('fast', function(){
            $("#screen-"+$(this).find("#scr-id").val()).slideDown('fast');
            $(this).remove();
        });
    });

    $("#table-screens").on("click", '#save-data', function(){

        if($("#the-title").val()==''){
            alert(jsLang.noTitle);
            return;
        }

        var params = {
            title: $("#the-title").val(),
            desc: $("#the-desc").val(),
            id: $("#scr-id").val(),
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            action: 'save-screen-data'
        };

        $.post('../ajax/upload-screens.php', params, function(data){

            if(data.error!=''){

                alert(data.message);

                if(data.token=='')
                    window.location.reload();

                $("#XOOPS_TOKEN_REQUEST").val(data.token);
                return;

            }

            html = '<strong>'+data.title+'</strong>';
            html += '<span class="rmc_options">';
            html += '<a href="#" class="edit-screen" id="edit-'+data.id+'">'+jsLang.edit+'</a>  |';
            html += '<a href="#" class="delete-screen" id="delete-'+data.id+'">'+jsLang.delete+'</a></span>';

            $("#screen-"+data.id+" td.the-title").html(html);
            $("#screen-"+data.id+" td.the-desc").html(data.description);

            $("#editor").slideUp('fast', function(){
                $("#screen-"+$(this).find("#scr-id").val()).slideDown('fast');
                $(this).remove();
            });

            $("#XOOPS_TOKEN_REQUEST").val(data.token);

        },'json');

    });
});