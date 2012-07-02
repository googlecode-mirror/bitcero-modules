<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('%s Screenshots','dtransport'), $sw->getVar('name')); ?></h1>

<a href="#" onclick="centerWindow(openWithSelfMain('../include/dtlistsoft.php','listsoft',500,300,true),500,300);"><{$lang_listsoft}></a>

<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell screens_uploader">
            <div id="images-uploader"></div>
            <div id="dt-errors"></div>
        </div>
        <div class="dt_cell">
            <form name="frmscreen" method="POST" action="screens.php">
                <table class="outer" width="100%" cellspacing="1" id="table-screens">
                    <thead>
                    <tr align="center">
                        <th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmscreen','checkAll')" /></th>
                        <th width="30"><?php _e('ID','dtransport'); ?></th>
                        <th width="80"><?php _e('Image','dtransport'); ?></th>
                        <th><?php _e('Title','dtransport'); ?></th>
                        <th><?php _e('Descripcion','dtransport'); ?></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr align="center">
                        <th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmscreen','checkAll')" /></th>
                        <th width="30"><?php _e('ID','dtransport'); ?></th>
                        <th width="80"><?php _e('Image','dtransport'); ?></th>
                        <th><?php _e('Title','dtransport'); ?></th>
                        <th><?php _e('Descripcion','dtransport'); ?></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php if(empty($screens)): ?>
                    <tr class="head">
                        <td colspan="7" align="center"><?php _e('There are not screenshots created for this download item!','dtransport'); ?></td>
                    </tr>
                        <?php endif; ?>
                    <?php foreach($screens as $screen): ?>
                    <tr class="<?php echo tpl_cycle('even,odd'); ?>" id="screen-<?php echo $screen['id']; ?>">
                        <td align="center"><input type="checkbox" name="id[]" id="item-<?php echo $screen['id']; ?>" value="<?php echo $screen['id']; ?>" /></td>
                        <td align="center" width="20"><strong><?php echo $screen['id']; ?></strong></td>
                        <td align="center"><a href="<?php echo str_replace('/ths','',$screen['image']); ?>" target="_blank"><img src="<?php echo $screen['image']; ?>" /></a></td>
                        <td>
                            <strong><?php echo $screen['title']; ?></strong>
                            <span class="rmc_options">
                                <a href="#" class="edit-screen" id="edit-<?php echo $screen['id']; ?>"><?php _e('Edit','dtransport'); ?></a>  |
                                <a href="#" class="delete-screen"><?php _e('Delete','dtransport'); ?></a>
                            </span>
                        </td>
                        <td><?php echo $screen['desc']; ?></td>
                    </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php echo $xoopsSecurity->getTokenHTML(); ?>
                <input type="hidden" name="op" />
                <input type="hidden" name="item" value="<{$item}>" />
            </form>

        </div>
    </div>
</div>
