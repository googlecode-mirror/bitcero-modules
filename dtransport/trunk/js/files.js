// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){

    $("#create-group").click(function(){
        if($("#group-name").val()==''){
            alert(jsLang.groupName);
            $("#group-name").focus();
            return false;
        }

        var params = $("#form-new-group").serialize();
        params += '&XOOPS_TOKEN_REQUEST='+$("#XOOPS_TOKEN_REQUEST").val();

        block_screen(1);
        $("#status-bar").slideDown('fast');

        $.post("../ajax/files-ajax.php", params, function(data){

            if(data.error==1){

                alert(data.message);
                if(data.token=='')
                    window.location.reload();

                $("#XOOPS_TOKEN_REQUEST").val(data.token);

                block_screen(0);
                $("#status-bar").slideUp('fast');

                $("#group-name").focus();

                return;

            }

            block_screen(0);
            $("#status-bar").slideUp('fast');

            $("#XOOPS_TOKEN_REQUEST").val(data.token);

            if(data.action=='create'){
                var html = '<tr class="head" id="group-'+data.id+'">';
                html += '<td colspan="6">'+data.name+'</td>';
                html += '<td align="center"><a href="files.php?item='+data.item+'&amp;edit=1&amp;id='+data.id+'">'+jsLang.edit+'</a> |';
                html += '<a href="files.php?item='+data.item+'&amp;id='+data.id+'&amp;action=deletegroup" class="deletegroup">'+jsLang.delete+'</a></td></tr>';
                $("#table-files tbody").append(html);
                $("#group-name").val('');
            } else {
                $("#group-"+data.id+" td:first-child").html(data.name);
                $("#group-name").val('');
                $("#cancel-edition").remove();
                $("input#id").remove();
                $("#create-group").val(jsLang.createGroup).removeClass('buttonGreen').addClass('buttonBlue');
                $("#form-new-group input[name=action]").val("save-group");
            }

            $("#group-"+data.id).effect("highlight", {}, 1000);


        }, 'json');

    });

    $("#remote").change(function(){
        if($(this).is(":checked")){
            $(".url-container").slideDown('fast', function(){
                $(this).children('input').effect('highlight',{},1000);
            });
            $("#dtfiles-uploader").fadeOut('fast');
            $("#dtfiles-preview").addClass("transparent");
        }else{
            $(".url-container").slideUp('fast');
            $("#dtfiles-preview").removeClass("transparent");
            if(!$("#dtfiles-preview").is(":visible"))
                $("#dtfiles-uploader").fadeIn('fast');
        }
    });

    $("#dtfiles-preview span.delete").click(function(){

        if(!confirm(jsLang.deleteFile)) return false;

        var params = {
            file: $("#dtfiles-preview span.name").html(),
            secure: $("#secure").val(),
            action: 'delete_hfile',
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val()
        };

        block_screen(1);
        $("#status-bar").slideDown('fast');

        $.post('../ajax/files-ajax.php', params, function(data){

            if(data.error==1){

                alert(data.message);
                block_screen(0);
                $("#status-bar").slideUp('fast');

                if(data.token=='')
                    window.location.reload();
                else
                    $("#XOOPS_TOKEN_REQUEST").val(data.token);

                return false;

            }

            $("#dtfiles-preview").fadeOut('fast', function(){
                $("#dtfiles-uploader").fadeIn('fast');
            });

            $("#XOOPS_TOKEN_REQUEST").val(data.token);

            block_screen(0);
            $("#status-bar").slideUp('fast');

        },'json');

    });

    $("#save-data").click(function(){

        // Check if a file has been provided
        if($("#remote").is(":checked")){

            if($("#url").val()==''){
                alert(jsLang.noURL);
                $("#url").focus();
                return false;
            }

        } else {

            if($("#dtfiles-preview span.name").html()==''){
                alert(jsLang.noFile);
                return false;
            }

        }

        if($("#title").val()==''){
            alert(jsLang.noTitle);
            $("#title").focus();
        }

        var params = {
            remote: $("#remote").is(":checked")?1:0,
            title: $("#title").val(),
            group: $("#group").val(),
            default: $("#default").is(":checked")?1:0,
            file: $("#remote").is(":checked")?$("#url").val():$("#dtfiles-preview span.name").html(),
            action: $("#action").val(),
            id: $("#id").length>0?$("#id").val():0,
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            item: $("#item").val(),
            secure: $("#secure").val(),
            mime: $("#dtfiles-preview span.type").html(),
            size: $("#size").val()
        };

        block_screen(1);
        $("#status-bar").slideDown('fast');

        $.post("../ajax/files-ajax.php", params, function(data){

            if(data.error==1){

                alert(data.message);
                $("#status-bar").slideUp('fast');
                block_screen(0);

                if(data.token=='')
                    window.location.reload();

                $("#XOOPS_TOKEN_REQUEST").val(data.token);

                return;

            }

            window.location.href = 'files.php?item='+$("#item").val();

        },'json');

        return true;

    });

    $("#table-files").on('click', 'a.deletegroup', function(){

        if(!confirm(jsLang.confirmDeletion)) return false;

        var url = $(this).attr("href")+"&XOOPS_TOKEN_REQUEST="+$("#XOOPS_TOKEN_REQUEST").val();

        window.location.href = url;
        return false;

    });

    $("#table-files").on('click', 'a.editgroup', function(){

        var id = $(this).parent().parent().attr("id").replace('group-','');
        var name = $("#group-"+id+" td:first-child").html();

        $("#group-name").val(name);
        $("#form-new-group").append("<input type='hidden' value='"+id+"' id='id' name='id' />");
        $("#create-group").val(jsLang.updateGroup).removeClass('buttonBlue').addClass('buttonGreen');
        if($("#cancel-edition").length<=0) $("#create-group").after('<input type="button" name="cancel" id="cancel-edition" value="'+jsLang.cancel+'" />');
        $("#form-new-group input[name=action]").val("update-group");
        $(".dt_group_form").effect('hightlight',{},1000);
        $("#group-name").focus();

        $("#cancel-edition").click(function(){

            $("#group-name").val('');
            $("input#id").remove();
            $("#create-group").val(jsLang.createGroup).removeClass('buttonGreen').addClass('buttonBlue');
            $("#form-new-group input[name=action]").val("save-group");
            $(this).remove();
            return true;

        });

        return false;

    });

    $("#table-files").on('change', 'select.group-selector', function(){

        var ele = $(this).parent().parent();
        var id = $(this).val();

        block_screen(1);
        $("#status-bar").slideDown('fast');
        var bg = $("#status-bar").css('background');

        // Send data
        var params = {
            action: 'reasign-file',
            idgroup: id,
            id: ele.find("input[type='checkbox']").attr("id").replace('item-',''),
            item: $("#item").val(),
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val()
        };

        if(id>0){
            if($("#group-"+id).length<=0) return;
        }

        $.post('../ajax/files-ajax.php', params, function(data){

            if(data.error==1){

                dt_show_error(data);
                return;

            }

            $("#XOOPS_TOKEN_REQUEST").val(data.token);
            $("#status-bar").html(data.message);
            $("#status-bar").css('background','#389931');
            block_screen(0, bg);

            $(ele).fadeOut('fast', function(){

                var two = ele.clone();

                if(id<=0){
                    ele.remove();
                    $("#table-files tbody").prepend(two);
                } else {

                    if($("#group-"+id).length<=0){
                        ele.fadeIn('fast');
                        return;
                    }

                    ele.remove();
                    $("#group-"+id).after(two);

                }

                two.fadeIn('fast');
                two.find("select").val(id);
            });

        }, 'json');

    });

    $("#table-files").on('click', 'a.delete-file', function(){

        if(!confirm(jsLang.confirmDeletion)) return false;

        var ele = $(this).parent().parent(); // tr

        var params = {
            id: ele.find("input[type='checkbox']").attr("id").replace("item-",''),
            item: $("#item").val(),
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            action: 'delete-file'
        }

        block_screen(1);
        $("#status-bar").slideDown('fast');
        var bg = $("#status-bar").css('background');

        $.post('../ajax/files-ajax.php', params, function(data){

            if(data.error==1){

                dt_show_error(data);
                return;

            }

            $("#XOOPS_TOKEN_REQUEST").val(data.token);
            $("#status-bar").html(data.message);
            $("#status-bar").css('background','#389931');
            block_screen(0, bg);

            ele.fadeOut('fast',function(){ele.remove();})

        });

        return false;

    });

});

function getFilesToken(){

    params = {
        identifier: $("#identifier").val(),
        action: 'identifier'
    };

    $.post('../ajax/files-ajax.php', params, function(data){

        if(data.error==1){
            alert(data.message);
            window.location.reload();
            return;
        }

        $("#XOOPS_TOKEN_REQUEST").val(data.token);

    },'json');

}