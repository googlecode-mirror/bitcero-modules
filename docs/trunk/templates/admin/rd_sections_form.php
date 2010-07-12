<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php $edit ? _e('Edit Section','docs') : _e('Create Section','docs'); ?></h1>
<form name="formSection" method="post" action="sections.php" id="frm-section">
<div id="rd-form-container" class="form">
    <input type="text" size="50" name="title" value="<?php $edit ? $sec->getVar('title') : ''; ?>" class="large" />
    <?php if($edit): ?>
    <div id="section-url"><strong>Permalink:</strong> <?php echo XOOPS_URL; ?>/<?php if($xoopsModuleConfig['permalinks']): ?><?php echo $xoopsModuleConfig['htpath']; ?><?php else: ?>modules/docs/<?php endif; ?></div>
    <?php else: ?>
    <div class="info"><?php _e('Remember to save this section in order to activate all options.','docs'); ?></div>
    <?php endif; ?>
    <?php echo $editor->render(); ?>
</div>
</form>