<h1 class="rmc_titles gs_titles"><span style="background-position: left;">&nbsp;</span><?php _e('Dashboard','galleries'); ?></h1>

<div class="gs_left">
    <div class="outer">
        <div class="th"><?php _e('Quick Overview','galleries'); ?></div>
        <div class="even">
            <div class="columns">
                <p><strong><?php _e('Albums:','galleries'); ?></strong> <span class="numbers"><a href="sets.php"><?php echo $set_count; ?></a></span></p>
                <p><strong><?php _e('Pictures:','galleries'); ?></strong> <span class="numbers"><a href="images.php"><?php echo $pic_count; ?></a></span></p>
                <p><strong><?php _e('Users:','galleries'); ?></strong> <span class="numbers"><a href="users.php"><?php echo $user_count; ?></a></span></p>
                <p><strong><?php _e('Tags:','galleries'); ?></strong> <span class="numbers"><a href="tags.php"><?php echo $tag_count; ?></a></span></p>
                <p><strong><?php _e('E-Cards:','galleries'); ?></strong> <span class="numbers"><a href="postcards.php"><?php echo $post_count; ?></a></span></p>
                <p><strong><?php _e('Space used:','galleries'); ?></strong> <span class="numbers"><?php echo $space; ?></span></p>
                <p><strong><?php _e('Files:','galleries'); ?></strong> <span class="numbers"><?php echo $file_count; ?>*</span></p>
                <p><strong><?php _e('First picture:','galleries'); ?></strong> <span class="numbers"><a href="<?php echo $first_pic['link']; ?>"><?php echo $first_pic['date']; ?></a></span></p>
            </div>
            <div class="moreinfo">
                <?php _e('* Could be more files than pictures if you have activated diferent formats in module settings.','galleries'); ?>
            </div>
        </div>
    </div>
    <br />
    <div class="outer">
        <div class="th"><?php _e('Last uploaded pictures','galleries'); ?></div>
        <div class="even" id="last-pics">
            <div><img src="../images/loader.gif" alt="" class="loader" /><br /><?php _e('Loading pictures...','galleries'); ?></div>
        </div>
        <div class="odd tright">
            <a href="images.php"><?php __('Manage Pictures','galleries'); ?></a>
        </div>
    </div>
    <br />
    <div class="outer">
        <div class="th"><?php _e('Last created albums','galleries'); ?></div>
        <div class="even" id="last-sets">
            <div><img src="../images/loader.gif" alt="" class="loader" /><br /><?php _e('Loading albums...','galleries'); ?></div>
        </div>
        <div class="odd tright">
            <a href="sets.php">Manage Albums</a>
        </div>
    </div>
    <br />
    <div class="outer">
        <div class="th"><?php _e('Top Users','galleries'); ?></div>
    </div>
</div>

<div class="gs_right">
    <div class="outer">
        <div class="th"><?php _e('MyGalleries News','galleries'); ?></div>
        <div class="even" id="gs-news">
            <div style="text-align: center; font-style: italic;"><img src="../images/loader.gif" alt="" class="loader" /><br /><?php _e('Loading news...','galleries'); ?></div>
        </div>
    </div>
    <br />
    
    <div class="outer">
        <div class="th"><?php _e('About MyGalleries','galleries'); ?></div>
        <div class="even">
            <div id="gs-desc">
                <div style="text-align: center; font-style: italic;"><img src="../images/loader.gif" alt="" class="loader" /><br /><?php _e('Loading news...','galleries'); ?></div>
            </div>
            <div id="gs-credits">
            
            </div>
        </div>
    </div>
</div>
