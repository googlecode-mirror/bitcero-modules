<?php if(!empty($this->crumbs)): ?>
<div class="rm_breadcrumb">
    <ul class="rm_breadcrumb_ul">
        <?php foreach($this->crumbs as $item): ?>
        <li>
        &raquo; 
        <?php if($item['link']!=''): ?>
            <a href="<?php echo $item['link']; ?>"<?php if($item['icon']!=''): ?> class="with_icon" style="background: url(<?php echo $item['icon']; ?>) no-repeat left;"<?php endif; ?>><?php echo $item['caption']; ?></a>
        <?php else: ?>
            <?php echo $item['caption']; ?>
        <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>