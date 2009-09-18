<div class="field_item">
	<div class="caption">
		<label for="date-format">Date format:</label>
		<span class="descriptions">Specify the date format according to <a href="http://www.php.net/manual/en/function.date.php" target="_blank">php date function</a>.</span>
	</div>
	<div class="content">
		<input type="text" name="xtconf_date_format" id="date-format" value="<?php echo $date_format; ?>" size="50" />
	</div>
</div>
<div class="field_item">
	<div class="content">
		<label><input type="checkbox" name="xtconf_cache" value="1" <?php echo $cache ? 'checked="checked"' : ''; ?>/> Enable cache</label>
		<span class="descriptions">By enabling this options the plugin will stored the data in a temporary cache file</span>
	</div>
</div>
<div class="field_item">
	<div class="caption">
		<label for="cache-time">Cache time:</label>
		<span class="descriptions">Set the time that the cache file will be maintained before to replace.</span>
	</div>
	<div class="content">
		<input type="text" name="xtconf_cache_time" id="cache-time" value="<?php echo $cache_time; ?>" size="5" />
		<select name="xtconf_cache_multi">
			<option value="1"<?php echo $cache_multi==1 ? 'selected="selected"' : ''; ?>>Seconds</option>
			<option value="60"<?php echo $cache_multi==60 ? 'selected="selected"' : ''; ?>>Minutes</option>
			<option value="3600"<?php echo $cache_multi==3600 ? 'selected="selected"' : ''; ?>>Hours</option>
			<option value="86400"<?php echo $cache_multi==86400 ? 'selected="selected"' : ''; ?>>Days</option>
		</select>
	</div>
</div>