<div class="mwitem" id="mwitem-<?php echo $post['id']; ?>">
    <h1><a href="<?php echo $post['link']; ?>" title="<?php _e('Permalink to this post','mywords'); ?>"><?php echo $post['title']; ?></a></h1>
    <span class="mwinfotop">
        <span class="mwcomments">
            <?php echo sprintf(__('%u Comments','mywords'), $post['comments']); ?>
        </span>
        <?php echo $post['published']; ?>. <?if($post['edit']): ?>&bull; <a href="<?php echo XOOPS_URL.'/modules/mywords/admin/posts.php?op=edit&amp;id='.$post['id']; ?>"><?php _e('Edit Post','mywords'); ?></a><?php endif; ?></span>
    <div class="mwtext">
        <?php echo $post['text'] ?>
    </div>
    <?php if($post['continue']): ?><span class="mwcontinue"><a href="<?php echo $post['link']; ?>#mwmore"><?php echo sprintf(__('Continue reading "%s"','mywords'), $post['title']); ?></a></span><?php endif; ?>
    <div class="mwfoot">
        <span class="mwcats">
        <?php if (count($post['cats'])>0): ?>
            <?php _e('Posted in','mywords'); ?>
            <?php foreach($post['cats'] as $i => $cat): ?>
                <a href="<?php echo $cat->permalink(); ?>"><?php echo $cat->getVar('name'); ?></a><?php echo $i<count($post['cats'])-1?', ':''; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </span>
        <span class="mwtags">
        <?php _e('Tagged as:','mywords'); ?>
        <?php foreach ($post['tags'] as $i => $tag): ?>
            <a href="<?php echo $tag->permalink(); ?>"><?php echo $tag->getVar('tag'); ?></a><?php echo $i<count($post['tags'])-1?', ':''; ?>
        <?php endforeach; ?>
        </span>
        <span class="mwbooks">
        <?php if($post['bookmarks']): ?>
            <?php foreach($post['bookmarks'] as $bm): ?>
                <a href="javascript:;" onclick="openWithSelfMain('<?php echo $bm['link']; ?>','marker',600,600);" title="<?php echo $bm['alt']; ?>"><img src="<?php echo XOOPS_URL; ?>/modules/mywords/images/icons/<?php echo $bm['icon']; ?>" alt="<?php echo $bm['alt']; ?>" /></a>
            <?php endforeach; ?>
        <?php endif; ?>
        </span>
    </div>
</div>