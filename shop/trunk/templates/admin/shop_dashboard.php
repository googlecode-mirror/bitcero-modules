<h1 class="rmc_titles shop_titles"><span style="background-position: left 0;">&nbsp;</span><?php _e('Dashboard','shop'); ?></h1>

<div id="shop-resume">
    <div class="outer">
        <div class="th"><?php _e('Quick Overview','shop'); ?></div>
        <div class="even info">
            <?php _e('Products registered:', 'shop'); ?> <span class="numbers"><?php echo $prods_count; ?></span>
            &nbsp; &nbsp;<a href="products.php"><?php _e('Manage products','shop'); ?> &raquo;</a>
        </div>
        <div class="even info">
            <?php _e('Categories created:', 'shop'); ?> <span class="numbers"><?php echo $cats_count; ?></span>
            &nbsp; &nbsp;<a href="categories.php"><?php _e('Manage categories','shop'); ?> &raquo;</a>
        </div>
        <div class="head info">
            <?php _e('Latest products:','shop'); ?>
        </div>
        <div class="even info">
            <ul class="recent">
                <?php if(empty($products)): ?>
                <li><?php _e('There are not products yet!','shop'); ?></li>
                <?php endif; ?>
            <?php foreach($products as $p): ?>
                <li style="background-image: url(<?php echo SHOP_UPURL; ?>/ths/<?php echo $p['image']; ?>);">
                    <a href="<?php echo $p['link']; ?>" title="<?php echo $p['name']; ?>"><span><?php echo $p['name']; ?></span></a>
                    <a href="products.php?action=edit&amp;id=<?php echo $p['id']; ?>"><?php _e('Edit','shop'); ?></a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <br />
    <div class="outer">
        <div class="th">
            <?php _e('MiniShop Information','shop'); ?>
        </div>
        <div class="even" id="mini-info">
            <div align="center" style="font-style: italic; font-size: 0.9em;"><img src="../images/loader.gif" alt="" /><br /><?php _e('Loading...','shop'); ?></div>
        </div>
    </div>
</div>

<div id="shop-news">
    <div class="outer">
        <div class="th"><?php _e('Recent MiniShop News','shop'); ?></div>
        <div class="even" id="shop-dsh-news">
            <div align="center" style="font-style: italic; font-size: 0.9em;"><img src="../images/loader.gif" alt="" /><br /><?php _e('Loading...','shop'); ?></div>
        </div>
    </div>
    <br />
    
    <div class="outer">
        <div class="th"><?php _e('About MiniShop', 'shop'); ?></div>
        <div class="even" id="shop-dsh-about">
            <div align="center" style="font-style: italic; font-size: 0.9em;"><img src="../images/loader.gif" alt="" /><br /><?php _e('Loading...','shop'); ?></div>
        </div>
    </div>
</div>