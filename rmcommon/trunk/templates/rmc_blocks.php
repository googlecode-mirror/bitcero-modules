<h1 class="rmc_titles"><?php _e('Blocks Administration','rmcommon'); ?></h1>

<div class="rmc_blocks_options">
    <label>
        <strong><?php _e('Module','rmcommon'); ?></strong><br />
        <select name="module">
            <option value=""><?php _e('All modules','rmcommon'); ?></option>
        <?php foreach($modules as $mod): ?>
            <option value="<?php echo $mod['mid']; ?>"><?php echo $mod['name']; ?></option>
        <?php endforeach; ?>
        </select>
    </label>
    <label>
        <strong><?php _e('Position','rmcommon'); ?></strong><br />
        <select name="position">
            <option value=""><?php _e('All positions','rmcommon'); ?></option>
            <?php foreach($positions as $pos): ?>
            <option value="<?php echo $pos['id']; ?>"><?php echo $pos['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</div>
<table class="outer" border="0">
    <tr><th>Hola</th></tr>
</table>
