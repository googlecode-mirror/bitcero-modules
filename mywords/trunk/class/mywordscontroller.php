<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the object MywordsController that
* will be uses by Common Utilities to do some actions
* like update comments
*/

class MywordsController
{
    public function update_comments_number($total, $comment){
        
        $db = Database::getInstance();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        if(!isset($post) || $post<=0) continue;
        
        $sql = "UPDATE ".$db->prefix("mw_posts")." SET comments=comments+1 WHERE id_post=$post";
        $db->queryF($sql);
    }
}
