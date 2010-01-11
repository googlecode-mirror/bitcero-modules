<div class="mwitem">
    <h1><a href="<?php echo $post['link']; ?>" title="<?php _e('Permalink to this post','mywords'); ?>"><?php echo $post['title']; ?></a></h1>
    <span class="mweditor"><?php echo $post['published']; ?>. <?if($post['edit']): ?>&bull; <a href="<?php echo XOOPS_URL.'/modules/mywords/admin/posts.php?op=edit&amp;id='.$post['id']; ?>"><?php _e('Edit Post','mywords'); ?></a><?php endif; ?></span>
    <div class="text">
        <?php echo $post['text'] ?>
    </div>
    <?php if($post['continue']): ?><span class="mwcontinue"><a href="<?php echo $post['link']; ?>#mwmore"><?php echo sprintf(__('Continue reading "%s"','mywords'), $post['title']); ?></a></span><?php endif; ?>
    <p class="mwfoot">
        <?php if (count($post['cats'])>0): ?>
        <?php _e('Posted in','mywords'); ?>
        
        <{if $post.categos!=''}><{$lang_postedin}> <span class="categos"><{$post.categos}><{if $post.comments!=''}> | <a href="<{$post.link}>#comments" title="<{$post.lang_comment}>"><{$post.comments}></a><{/if}></span><{/if}>
        <?php endif; ?>
        <{if $post.bookmarks}><br />
            <{foreach item=bm from=$post.bookmarks}>
                <a href="javascript:;" onclick="openWithSelfMain('<{$bm.link}>','marker',600,600);" title="<{$bm.alt}>"><img src="<{$xoops_url}>/modules/mywords/images/icons/<{$bm.icon}>" alt="<{$bm.alt}>" /></a>
            <{/foreach}>
        <{/if}>
    </p>
</div>