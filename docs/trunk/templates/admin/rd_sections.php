<?php
function rd_print_sections($sections,$id, $table=true){
    if($table): ?>
<?php foreach($sections as $section): ?>
	<tr align="center" valign="top" class="<?php echo tpl_cycle("even,odd"); ?>" id="sec-<?php echo $section['parent']; ?>-<?php echo $section['id']; ?>">
		<td align="left">
            <strong><?php echo $section['number']; ?>.
            <a href="?action=edit&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php echo $section['title']; ?></a></strong>
            <span class="rmc_options">
                <a href="./sections.php?action=edit&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Edit','docs'); ?></a> |
                <a href="./sections.php?action=delete&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>" onclick="return confirm('<?php echo sprintf(__("Do you really wish to delete %s?",'docs'), $section['title']); ?>');"><?php _e('Delete','docs'); ?></a> |
                <a href="?action=new&amp;id=<?php echo $id; ?>&amp;parent=<?php echo $section['id']; ?>"><?php _e('Add Section','docs'); ?></a> |
                <a href="<?php echo $section['link']; ?>">View</a>
            </span>
        </td>
        <td><?php echo $section['author_name']; ?></td>
        <td><?php echo $section['created']; ?></td>
        <td><?php echo $section['modified']; ?></td>
        <td><?php echo $section['comments']; ?></td>
	</tr>
	<?php 
        rd_print_sections($section['sections'], $id);
        endforeach; ?>
<?php else : ?>
    <?php if(!empty($sections)): ?>
    <ol>
        <?php foreach($sections as $section): ?>
        <li id="list_<?php echo $section['id']; ?>"><div><?php echo $section['title']; ?></div>
        <?php rd_print_sections($section['sections'], $id, false); ?>
        </li>
        <?php endforeach; ?>
    </ol>
    <?php endif; ?>
<?php
    endif;
}

?>
<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php if(!isset($res)): _e('Sections Management','docs'); else: echo sprintf(__('Sections in %s','docs'), $res->getVar('title')); endif; ?></h1>

<form name="frmsec" method="POST" action="sections.php" id="frm-sections">
<div class="rd_loptions">
    <strong><a href="resources.php"><?php _e('Choose another Document','docs'); ?></a></strong> |
    <a href="sections.php?id=<?php echo $id; ?>"><?php _e('List Sections','docs'); ?></a> |
    <a href="sections.php?id=<?php echo $id; ?>&amp;action=new"><?php _e('New Section','docs'); ?></a> |
    <a href="#" id="start-sortable"><?php _e('Sort Sections','docs'); ?></a>
    <?php RMEvents::get()->run_event('docs.get.sections.options'); ?>
</div>
<div id="table-sections">
<table class="outer" width="100%" cellspacing="0">
    <thead>
	<tr class="head" align="center">
		<th align="left"><?php _e('Title','docs'); ?></th>
        <th><?php _e('Author','docs'); ?></th>
        <th><?php _e('Created','docs'); ?></th>
        <th><?php _e('Updated','docs'); ?></th>
        <th><img src="../images/comment.png" alt="<?php _e('Comments','docs'); ?>" title="<?php _e('Comments','docs'); ?>" /></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th align="left"><?php _e('Title','docs'); ?></th>
        <th><?php _e('Author','docs'); ?></th>
        <th><?php _e('Created','docs'); ?></th>
        <th><?php _e('Updated','docs'); ?></th>
        <th><img src="../images/comment.png" alt="<?php _e('Comments','docs'); ?>" title="<?php _e('Comments','docs'); ?>" /></th>
    </tr>
    </tfoot>
    <tbody>
        <?php if(empty($sections)): ?>
        <tr align="center" class="even">
            <td colspan="6"><?php _e('There are not sections created in this document!','docs'); ?></td>
        </tr>
        <?php endif; ?>
	<?php foreach($sections as $section): ?>
	<tr align="center" valign="top" class="<?php echo tpl_cycle("even,odd"); ?>" id="sec-<?php echo $section['parent']; ?>-<?php echo $section['id']; ?>">
		<td align="left">
            <strong><?php echo $section['number']; ?>.
            <a href="?action=edit&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php echo $section['title']; ?></a></strong>
            <span class="rmc_options">
                <a href="./sections.php?action=edit&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Edit','docs'); ?></a> |
                <a href="./sections.php?action=delete&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>" onclick="return confirm('<?php echo sprintf(__("Do you really wish to delete %s?",'docs'), $section['title']); ?>');"><?php _e('Delete','docs'); ?></a> |
                <a href="?action=new&amp;id=<?php echo $id; ?>&amp;parent=<?php echo $section['id']; ?>"><?php _e('Add Section','docs'); ?></a> |
                <a href="<?php echo $section['link']; ?>">View</a>
            </span>
        </td>
        <td><?php echo $section['author_name']; ?></td>
        <td><?php echo $section['created']; ?></td>
        <td><?php echo $section['modified']; ?></td>
        <td><?php echo $section['comments']; ?></td>
	</tr>
	<?php 
     rd_print_sections($section['sections'], $id);
        endforeach; ?>
    </tbody>
</table>
</div>
<div id="sections-sortable">
    <a href="#" class="save-sortable"><?php _e('Save Positions','docs'); ?></a>
    <a href="#" class="cancel-sortable"><?php _e('Cancel','docs'); ?></a>
    <ol class="sec_connected">
        <?php foreach($sections as $section): ?>
        <li id="list_<?php echo $section['id']; ?>"><div><?php echo $section['title']; ?></div>
        <?php rd_print_sections($section['sections'], $id, false); ?>
        </li>
        <?php endforeach; ?>
    </ol>
    <a href="#" class="save-sortable"><?php _e('Save Positions','docs'); ?></a>
    <a href="#" class="cancel-sortable"><?php _e('Cancel','docs'); ?></a>
</div>
    
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</form>
<div id="rd-wait"><img src="../images/wait.gif" alt="" /><br /><?php _e('Saving','docs'); ?><br /><?php _e('...','docs'); ?></div>
