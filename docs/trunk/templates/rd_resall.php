<a name="rd_top"></a>
<!-- Table of Contents -->
<?php include RMTEmplate::get()->get_template('rd_resindextoc.php', 'module', 'docs'); ?>
<!-- /Table of Contents -->

<!-- Resource Content -->
<?php foreach($toc as $sec): ?>
    <?php include RMTemplate::get()->get_template('rd_item.php','module','docs'); ?>
<?php endforeach; ?>
<!-- /End resource content -->

<!-- Notes and references -->
<?php include RMTemplate::get()->get_template('rd_notes_and_refs.php','module','docs'); ?>
<!-- /End Notes and references -->

<!-- Comments -->
<h3><?php _e('Comments','docs'); ?></h3>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_display.html"); ?>
<?php echo $xoopsTpl->fetch(RMCPATH."/templates/rmc_comments_form.html"); ?>
<!-- End Comments -->