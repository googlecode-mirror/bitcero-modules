<?php
// $Id$
// --------------------------------------------------------------
// Red M�xico Common Utilities
// A framework for Red M�xico Modules
// Author: Eduardo Cort�s <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

interface iCommentsController
{
    /**
     * Add 1 to comments internal counter of the module
     * @param RMComment
     */
    public function increment_comments_number($comment);
    /**
     * Reduce in 1 the comments internal counter of the module
     * @param RMComment
     */
    public function reduce_comments_number($comment);
    /**
     * Get the element related to a specific comment
     * @param string Parameters to identify the comment
     * @param RMComment Comment object
     * @param bool Return item with url or without
     * @return string
     */
    public function get_item($params, $com);
    /**
     * Get the element url
     * @param string Parameters to identify the comment
     * @param RMComment Comment object
     * @return string
     */
    public function get_item_url($params, $com);
    /**
     * Return the link to module or element owner of the comment
     * @return string
     */
    public function get_main_link();
}