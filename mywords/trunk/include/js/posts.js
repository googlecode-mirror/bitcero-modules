function in_array(val,array){
	var a=false;
	for (var i=0;i<array.length;i++){
		if (val.toLowerCase()==array[i].toLowerCase()){
			return true;
			break;
		}
	}
	
	return false;
}

function array_slice(array, val){
	for (var i=0;i<array.length;i++){
		if (val==array[i]){
            array[i] = '';
			//return array.slice(i+1,1);
		}
	}
    
    return array;
}

$(document).ready( function($) {
    
    var total_tags = 0;
    var tip_tag_visible = false;
    
    $("#publish-submit").click(function() {
        if ($("#status option[value='publish']").val()==undefined){
            $("#status").html($("#status").html() + "<option value='publish'><?php _e('Published','admin_mywords'); ?></option>");
        }
        $("#status option:selected").removeAttr("selected");
        $("#status option[value='publish']").attr("selected", true);
        
    });
    
    // Save as
    $("#saveas").click(function() {
    	$("#action").val('save-return');
	});

    $("#edit-publish").click(function() {
    	$("#edit-publish").hide();
        $("#publish-options").slideDown("fast");
    });
    
    $("#publish-ok").click(function() {
    	if ($("#status").val()=='draft'){
			$("#publish-status-legend").text('<?php _e('Draft','admin_mywords'); ?>');
			$("#saveas").val('<?php _e('Save as Draft','admin_mywords'); ?>');
    	} else if($("#status").val()=='pending') {
			$("#publish-status-legend").text('<?php _e('Pending Review','admin_mywords'); ?>');
			$("#saveas").val('<?php _e('Save as Pending','admin_mywords'); ?>');
    	} else {
            $("#publish-status-legend").text('<?php _e('Published','admin_mywords'); ?>');
        }
	
		$("#publish-options").slideUp("fast");
		$("#edit-publish").show();
    });

    $("#visibility-edit").click(function() {
        $("#visibility-edit").hide();
        $("#visibility-options").slideDown('fast');
    });

    $("input[name=visibility]").click(function() {
        if ($(this).val()=='password'){
            $("#vis-password-text").slideDown();
        } else {
            $("#vis-password-text").slideUp();
        }
    });

    $("#vis-button").click(function() {

        //Verificamos el password
        $("#vis-password").val($("#vis-password").val().replace(/[ ]/gi,""));
        if ($("#vis-password").val()=='' && $("#visibility-options input[type=radio]:checked").val()=='password'){
            $("#vis-password-text").addClass("span-error");
            $("#vis-password-text").html($("#vis-password-text").html() + "<strong><?php _e('Password:','admin_mywords'); ?></strong>");
            return;
        }
		
		// Verificamos si es privado
		if ($("#visibility-options input[type=radio]:checked").val()=='private'){
			$("#edit-publish").hide();
            $("#publish-status-legend").text('<?php _e('Private','admin_mywords'); ?>');
            $("#saveas").hide();
		} else {
            $("#edit-publish").show();
            $("#saveas").show();
            if ($("#status").val()=='draft'){
    			$("#publish-status-legend").text('<?php _e('Draft','admin_mywords'); ?>');
            } else if ($("#status").val()=='publish'){
                $("#publish-status-legend").text('<?php _e('Published','admin_mywords'); ?>');
        	} else {
    			$("#publish-status-legend").text('<?php _e('Pending Review','admin_mywords'); ?>');
        	}
		}
		
        $("#vis-password-text").removeClass("span-error");
        $("#vis-password-text strong").text('');

        text = $("label[for="+$("#visibility-options input[type=radio]:checked").attr('id')+"]").text();
        $("#visibility-caption").text(text);
        $("#visibility-options").slideUp('fast');
        $("#visibility-edit").show();
    });
	
	$("#vis-cancel").click(function() {
		$("#visibility-options").slideUp('fast');
		$("#visibility-edit").show();
		
	});

    $(".edit-schedule").click(function() {
        $(".edit-schedule").hide();
        $(".schedule-options").slideDown('fast');
    });

    $("a.schedule-cancel").click(function() {
        d = $("input#schedule").val().split("-");
        $("#schedule-day").val(d[0]);
        $("#schedule-month option:selected").removeAttr("selected");
        $("#schedule-month option[value="+d[1]+"]").attr("selected", true);
        $("#schedule-year").val(d[2]);
        $("#schedule-hour").val(d[3]);
        $("#schedule-minute").val(d[4]);
        $(".schedule-options").slideUp('fast');
        $(".edit-schedule").show();
    });
    
    $("#schedule-ok").click(function(){
    	current = <?php echo time() ?>;
        schedule = mktime($("#schedule-hour").val(), $("#schedule-minute").val(), 0, $("#schedule-month").val(), $("#schedule-day").val(), $("#schedule-year").val());
        // Check if scheduled date is minor than current date
        if (schedule<=current+3600){
        
            schedule = current;
            day = <?php echo(date('d',time())) ?>;
            month = <?php echo(date('n',time())) ?>;
            year = <?php echo(date('Y',time())) ?>;
            hour = <?php echo(date('H',time())) ?>;
            minute = <?php echo(date('i',time())) ?>;
            $("input#schedule").val(day+'-'+month+'-'+year+'-'+hour+'-'+minute);
            $("#schedule-day").val(day);
            $("#schedule-year").val(year);
            $("#schedule-hour").val(hour);
            $("#schedule-minute").val(minute);
            $("#schedule-month option:selected").removeAttr("selected");
            $("#schedule-month option[value="+month+"]").attr("selected", true);
            $("strong#schedule-caption").text('<?php _e('Inmediatly','admin_mywords'); ?>');
            
        } else {
            
            $("input#schedule").val(schedule);
            val = $("#schedule-day").val() + '-' + $("#schedule-month").val() + '-' + $("#schedule-year").val() + '-' + $("#schedule-hour").val() + '-' + $("#schedule-minute").val();
            d = val.split("-");
            $("input#schedule").val(val);
            $("strong#schedule-caption").text(d[0]+', '+$("#schedule-month option[value="+d[1]+"]").text()+' '+d[2]+' @ '+d[3]+':'+d[4]);
            
        }
        
        $(".schedule-options").slideUp('fast');
        $(".edit-schedule").show();
        
    });
    
    // Tags
    $("input#tags-button").click(function(){
		tag = $("input#tags-m").val();
		if (tag=='') return;
		tags = tag.split(',');
		
		// Sanitize tags
		for (var j=0;j<tags.length;j++){
			tags[j] = tags[j].replace(/^\s*|\s*$/g,'');
			tags[j] = tags[j].replace(/[ ]{2,}/gi," ");
		}
		
		j = 0;
		
		spans = $("div#tags-container label");
		$(spans).each(function(i){
			text = $(this).text().replace(" ","");
			text = text.replace("&nbsp;");
			text = text.replace(/^\s*|\s*$/g,'');
			if (in_array(text,tags)){
				tags = array_slice(tags,text);
			}
		});
		
		for (j=0;j<tags.length;j++){
            if (tags[j]=='') continue;
            total_tags++;
			$("div#tags-container").append("<label><input type='checkbox' name='tags[]' checked='checked' value='"+tags[j]+"' /> "+tags[j]+"</label>");
		}
		$("input#tags-m").val('');
        
        if (total_tags>0 && !tip_tag_visible){
			$("div#tags-container span.tip_legends").show();
			tip_tag_visible = true;
        }
		
    });
    
    $("input#tags").keydown(function(e) { if(e.which == 13){ $("input#tags-button").click(); return false; } });
    
    // Popular Tags
    $("a#show-used-tags").click(function() {
		$("div#popular-tags-container").slideDown('slow');
    });
    
    $("a.add_tag").click(function() {
		labels = $("div#tags-container label");
		tag = $(this).text();
		found = false;
		$(labels).each(function(i){
			text = $(this).text().replace(" ","");
			text = text.replace("&nbsp;");
			text = text.replace(/^\s*|\s*$/g,'');
			if (text==tag){
				found = true;
			}
		});
		
		if (!found)
			$("div#tags-container").append("<label><input type='checkbox' name='tags[]' checked='checked' /> "+tag+"</label>");
		
		if (!tip_tag_visible)
			$("div#tags-container span.tip_legends").show();
		
    });
    
    $("a.mw_show_metaname").click(function(){
		$("select#meta-name-sel").hide();
		$("input#meta-name").show();
		$("input#meta-name").focus();
		$("a.mw_show_metaname").hide();
		$("a.mw_hide_metaname").show();
    });
    
    $("a.mw_hide_metaname").click(function(){
    	$("input#meta-name").hide();
		$("select#meta-name-sel").show();
		$("select#meta-name-sel").focus();
		$("a.mw_hide_metaname").hide();
		$("a.mw_show_metaname").show();
    });
    
    $("input#mw-addmeta").click(function(){
		if ($("select#meta-name-sel").is(":visible")){
			var name = $("select#meta-name-sel").val();
		} else {
			var name = $("input#meta-name").val()
		}
		
		if (name==''){
			$("label#error-metaname").slideDown('fast');
			return;
		}
		
		var value = $("textarea#meta-value").val();
		if (value==''){
			$("label#error-metavalue").slideDown('fast');
			return;
		}
		
		$("label#error-metaname").hide();
		$("label#error-metavalue").hide();
		
		var exit = false;
		if ($("table#metas-container input").length>0){
			$("table#metas-container input").each(function(){
				if ($(this).val()==name){
					alert('<?php _e('There is already a meta with same name','admin_mywords'); ?>');
					exit = true;
					return;
				}
			});
		}
		
		if (exit) return;
		
        var count = 0;
        $("table#metas-container input").each(function(){
            id = $(this).attr("id").substring(0, 8);
            if (id=='meta-key'){
                num = $(this).attr("id").replace("meta-key-","");
                if (count <= num)
                    count = num;
            }
        });
        
        count++;
		
		$("table#metas-container").show();
		var html = '<tr class="even">';
		html += '<td valign="top"><input type="text" name="meta['+count+'][key]" id="meta-key-'+count+'" value="'+name+'" class="mw_large" style="width: 95%;" />';
		html += '<a href="javascript:;" onclick="remove_meta($(this));"><?php _e('Remove','admin_mywords'); ?></td>';
		html += '<td><textarea name="meta['+count+'][value]" id="meta['+count+'][value]" class="mw_large">'+value+'</textarea></td></tr>';
		$("table#metas-container").append(html);
		
		$("select#meta-name-sel option[selected='selected']").removeAttr('selected');
		$("select#meta-name-sel option[value='']").attr("selected",'selected');
		$("textarea#meta-value").val('');
		$("input#meta-name").val('');
		
		$("tr#row-"+count).effect('highlight',{},'2000');
		
    });
    
    $("input#publish-submit").click(function(){
        
        $('div#mw-messages-post').slideUp('slow',function(){
            $('div#mw-messages-post').html('');
        });
        
        if($("input#post-title").val()==''){
            $("label[for='post-title']").slideDown();
            return false;
        }
        
        if(tinyMCE){
            tinyMCE.activeEditor.save();
        }
        
        if ($("#content").val()==''){
            alert('<?php _e('Add content before to save this post','admin_mywords'); ?>');
            return false;
        }
        
        // Serialize all data
        var params = $("form#mw-form-posts").serialize();
        params += "&"+$("form#mw-post-publish-form").serialize();
        params += "&"+$("form#mw-post-categos-form").serialize();
        params += "&"+$("form#mw-post-tags-form").serialize();
        
        // Send Post data
        $.post('ajax/ax-posts.php', params, function(data){
            if(data['error']!=undefined && data['error']!=''){
                $('div#mw-messages-post').addClass('messages_error');
                $('div#mw-messages-post').html(data['error']);
                $('div#mw-messages-post').slideDown();
                if(data['token'])
                    $('#XOOPS_TOKEN_REQUEST').val(data['token']);
                return;
            }
            
            window.location.href = 'posts.php?op=edit&id='+data['post'];
            
        },'json');
        
        return false;
        
    });

 })(jQuery);
 
 function remove_meta(id){
	 
	 var parent = $(id).parent();
	 parent = $(parent).parent();
	 $(parent).remove();
	 
 }