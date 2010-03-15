<h1 class="rmc_titles mw_titles"><span style="background-position: -96px 0;">&nbsp;</span><?php _e('Dashboard','mywords'); ?></h1>

<div id="mw_widgets_container">

    <div class="mw_right_widgets">
        <!-- Quick publishing -->
        <div class="outer">
            <div class="th"><?php _e('MyWords Resources','mywords'); ?></div>
            <div class="even mw_tools">
            	<a href="http://redmexico.com.mx/docs/mywords" target="_blank" class="item">
            		<?php _e('MyWords documentation','mywords'); ?><br />
            		<span><?php _e('Learn more about MyWords. Installation, configuration and all information to improve thsi module.','mywords'); ?>
            	</a>
            </div>
            <div class="even mw_tools">
            	<a href="http://redmexico.com.mx/" target="_blank" class="item">
            		<?php _e('Red México','mywords'); ?><br />
            		<span><?php _e('New modules, themes and awesome resources for XOOPS.','mywords'); ?></span>
            	</a>
            	<?php
            		// Print new resources
            		RMEvents::get()->run_event('mywords.get.resources.list');
            	?>
            </div>
        </div>
        <!-- / End quick publishing -->
        
        <!-- Recent News -->
        <div class="outer" id="mw-recent-news">
        	<div class="th"><?php _e('Recent News','mywords'); ?></div>
        	
        </div>
        <!-- /End recent news -->
        
        <!-- Other blocks -->
        <?php RMEvents::get()->run_event('mywords.dashboard.right.widgets'); ?>
        <!-- /End other blocks -->
    </div>
    
    <div class="mw_left_widgets">
        <!-- Quick overview -->
        <div class="outer">
            <div class="th"><?php _e('Quick Overview','mywords'); ?></div>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr class="mw_qrdata">
                	<td align="right" width="20"><a href="posts.php"><span><?php echo $numposts; ?></span></td>
                    <td><a href="posts.php"><?php _e('Posts', 'admin_mywords'); ?></a></td>
                    <td align="right" width="20"><a href="<?php echo RMCURL; ?>/comments.php?module=mywords"><span><?php echo $numcoms; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/comments.php?module=mywords"><?php _e('Comments','mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=draft"><span><?php echo $numdrafts; ?></span></a></td>
                	<td><a href="posts.php?status=draft"><?php _e('Drafts','mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/editors.php"><span><?php echo $numeditors; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/editors.php"><?php _e('Editors','mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=pending"><span><?php echo $numpending; ?></span></a></td>
                	<td><a href="posts.php?status=pending"><?php _e('Pending of Review','mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/bookmarks.php"><span><?php echo $numsocials; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/bookmarks.php"><?php _e('Social sites','mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="categories.php"><span><?php echo $numcats; ?></span></a></td>
                	<td><a href="categories.php"><?php _e('Categories','mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/tags.php"><span><?php echo $numtags; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/tags.php"><?php _e('Tags','mywords'); ?></a></td>
                </tr>
            </table><br />
            <span class="descriptions">Esta es la descripcion</span>
        </div>
        <!-- / End quick overview -->
        
        <!-- Drafts -->
        <div class="outer">
        	<div class="th"><?php _e('Recent Drafts', 'admin_mywords'); ?></div>
        	<?php foreach($drafts as $post): ?>
        	<div class="even mw_tools">
        		<a href="posts.php?op=edit&amp;id=<?php echo $post->id(); ?>" class="item">
        			<?php echo $post->getVar('title'); ?><br />
        			<span><?php echo substr(strip_tags($post->content(true)), 0, 150).'...'; ?></span>
        		</a>
        		
        	</div>
        	<?php endforeach; ?>
        </div>
        <!-- / End Drafts -->
        
        <!-- Pending of review -->
        <div class="outer">
        	<div class="th"><?php _e('Posts pending for review', 'admin_mywords'); ?></div>
        	<?php foreach($pendings as $post): ?>
        	<div class="even mw_tools">
        		<a href="posts.php?op=edit&amp;id=<?php echo $post->id(); ?>" class="item">
        			<?php echo $post->getVar('title'); ?><br />
        			<span><?php echo substr(strip_tags($post->content(true)), 0, 150).'...'; ?></span>
        		</a>
        		
        	</div>
        	<?php endforeach; ?>
        </div>
        <!-- / End Pending of Review -->
        
        <!-- Other blocks -->
        <?php RMEvents::get()->run_event('mywords.dashboard.left.widgets'); ?>
        <!-- /End other blocks -->
        
        <!-- Credits -->
        <div class="outer">
        	<div class="th"><?php _e('Credits', 'admin_mywords'); ?></div>
        	<div class="even mw_tools">
        	<ul>
        		<li><?php _e('MyWords has been created by <strong><a href="http://redmexico.com.mx">Red México</a></strong>', 'admin_mywords'); ?></li>
        		<li><?php _e('And developed by <strong>BitC3R0</strong>','mywords'); ?></li>
        		<li><?php _e('You can contact me trough <strong>i.bitcero@gmail.com</strong>','amin_mywords'); ?></li>
        		<li><?php _e('Some icons has been designed by <a href="http://dryicons.com/free-icons/">dryicons</a>.','mywords'); ?></li>
        	</ul>
        	</div>
        </div>
        <!-- /End credits -->
        
    </div>
    
</div>
