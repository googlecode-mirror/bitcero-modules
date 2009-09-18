<div class="field_item">
	<div class="caption">
		<label for="display">Display:</label>
		<span class="descriptions">Select the total number and type of phothos that will be shown.</span>
	</div>
	<div class="content">
		<select name="xtconf_num_items" id="display">
		<?php for($i=1;$i<=20;$i++){ ?>
			<option value="<?php echo $i ?>"<?php echo ($num_items==$i ? ' selected="selected"' : ''); ?>><?php echo $i; ?></option>
		<?php } ?>
		</select>

		<select name="xtconf_type" id="type-sel">
		<?php $types = array('user','set','favorite','group','public');
				foreach ($types as $t){ ?>
		    <option value="<?php echo $t; ?>"<?php echo ($type==$t ? " selected='selected'" : ''); ?>><?php echo $t; ?></option>
		<?php }
				unset($types); ?>
		</select> photos
	</div>
</div>
<div class="field_item<?php echo ($type!='user' && $type!='set' && $type!='favorite' ? ' it_hidden' : ''); ?>" id="user-id-field">
	<div class="caption">
		<label for="user-id">User ID:</label>
	</div>
	<div class="content">
		<input type="text" name="xtconf_id" id="user-id" size="20" value="<?php $id; ?>" />
		<a href="javascript:;" class="idgetter">Find your id</a>
	</div>
</div>

<div class="field_item<?php echo ($type!='set' && $type!='user' ? ' it_hidden' : ''); ?>" id="set-id-field">
	<div class="caption">
		<label for="set-id">Set ID:</label>
	</div>
	<div class="content">
		<input type="text" name="xtconf_set" id="set-id" size="20" value="<?php $set; ?>" />
		Use number from the url
	</div>
</div>

<div class="field_item<?php echo ($type!='group' ? ' it_hidden' : ''); ?>" id="group-id-field">
	<div class="caption"><label for="group-id">Group ID:</label></div>
	<div class="content">
		<input type="text" name="xtconf_group" id="group-id" size="20" value="<?php echo $group; ?>" />
		<a href="javascript:;" class="idgetter">Find your group id</a>
	</div>
</div>

<div class="field_item" id="tags-field">
	<div class="caption"><label for="tags">Tags (<em>optional</em>):</label></div>
	<div class="content">
		<input type="text" name="xtconf_tags" id="tags" size="50" value="<?php echo $tags; ?>" />
		Comma separated
	</div>
</div>

<div class="field_item">
	<div class="content">
		Add to your Smarty template the <code><{ithemes_process action="load" plugin="flickr"}></code> in order to activate Flickr Plugin.
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#type-sel").change(function(){
			var val = $("#type-sel option:selected").text();
			switch(val){
				case 'user':
					$("#user-id-field").show();
					$("#group-id-field").hide();
					$("#set-id-field").hide();
					$("#tags-field").show();
					break;
				case 'set':
					$("#user-id-field").show();
					$("#set-id-field").show();
					$("#tags-field").hide();
					$("#group-id-field").hide();
					break;
				case 'group':
					$("#user-id-field").hide();
					$("#set-id-field").hide();
					$("#tags-field").hide();
					$("#group-id-field").show();
					break;
				case 'favorite':
					$("#user-id-field").show();
					$("#set-id-field").hide();
					$("#tags-field").hide();
					$("#group-id-field").hide();
					break;
				case 'public':
					$("#user-id-field").hide();
					$("#set-id-field").hide();
					$("#tags-field").show();
					$("#group-id-field").hide();
					break;
			}
			
			$(".idgetter").click(function(event){
				var group = $("#type-sel").val()=="group";
				
				window.open('http://idgettr.com');
				
			});
			
		});
		
	});
</script>