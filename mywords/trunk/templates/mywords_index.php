<?php
    foreach (RMTemplate::get()->get_var('posts') as $post){
        include RMTemplate::get()->get_template('mywords_single_post.php','module','mywords');
    }