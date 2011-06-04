<?php

$tf = new RMTimeFormatter(0, '%M% %d%, %Y%');

?>
<div class="mch_tf_icontainer">
<?php foreach ($teams as $team): ?>
<div class="mch_tf_item" id="item-tf-<?php echo $team['id_team']; ?>">
    <?php if($team['logo']!=''): ?><img src="<?php echo MCH_UP_URL; ?>/<?php echo $team['logo']; ?>" alt="<?php echo $team['name']; ?>" /><?php endif; ?>
    <strong><?php echo $team['name']; ?></strong>
    <span class="category"><?php echo $team['category_object']['name']; ?></span>
    <span class="date"><?php echo $tf->format($team['created']); ?></span>
</div>
<?php endforeach; ?>
</div>