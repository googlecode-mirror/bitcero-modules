var upload_error = 0;
$(document).ready(function(){

    $("#images-uploader").uploadify({
        'swf': '<?php echo RMCURL; ?>/include/uploadify.swf',
        'uploader': '<?php echo DT_URL; ?>/ajax/upload-screens.php',
        'auto': 1,
        'buttonCursor': 'hand',
        'buttonText': '<?php _e("Select Screenshots...","dtransport"); ?>',
        'fileObjName': 'Filedata',
        'fileSizeLimit': '<?php echo $xoopsModuleConfig["image"]*1024; ?>',
        'fileTypeDesc': '<?php _e("Image files (*.gif; *.jpg; *.png)","dtransport"); ?>',
        'fileTypeExts': '*.gif;*.jpg;*.png',
        'formData': {
            action: "upload",
            item: '<?php echo $sw->id(); ?>',
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            data: "<?php echo $tc->encrypt($_SERVER['HTTP_USER_AGENT'].'|'.session_id().'|'.$xoopsUser->uid().'|'.$rmf->current_url()); ?>"
        },
        'height': 30,
        'method': 'post',
        'preventCaching': 1,
        'progressData': 'percentage',
        'queueSizeLimit': 100,
        'removeCompleted': 1,
        'removeTimeout': 1,
        'successTimeout': 30,
        'uploadLimit': 999,
        'width': 150,
        'onUploadStart': function(file){
            $('#dt-errors .error').fadeOut('fast');
        },
        'onQueueComplete': function(event, data){
            //if(upload_error==1) return;
            //$('#dt-errors .error').fadeOut('fast');
        },
        'onUploadSuccess': function(file, resp, data){
            
            alert(resp);
            eval('ret = '+resp);
            if (ret.error==1){
                $('#dt-errors').append('<div class="error">'+ret.message+'</div>').fadeIn('fast');
                upload_error = 1;
            } else {
                upload_error = 0;
                if($("#table-screens .head").length>0)
                    $("#table-screens .head").remove();

                var html = '<tr class="even" id="screen-'+ret.id+'" align="center" style="display: none;">';
                html += '<td><strong>'+ret.id+'</strong></td>';
                html += '<td><a href="'+ret.dir+'/'+ret.image+'" target="_blank"><img src="'+ret.dir+'/ths/'+ret.image+'" /></a></td>';
                html += '<td align="left"><strong>'+ret.image+'</strong>';
                html += ' <span class="rmc_options">';
                html += '<a href="#" class="edit-screen" id="edit-'+ret.id+'">'+jsLang.edit+'</a>  |';
                html += '<a href="#" class="delete-screen" id="delete-'+ret.id+'">'+jsLang.delete+'</a></span>';
                html += '</td><td>&nbsp;</td>';
                html += '</tr>';
                $("#table-screens tbody").prepend(html);
                $("#screen-"+ret.id).fadeIn('fast', function(){
                    $("#screen-"+ret.id+" td").effect('highlight', {}, 1000);
                });
                alert(ret.token);

            }
            return true;
        }
    });
});
