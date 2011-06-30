<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
?>
<script type="text/javascript">
$(document).ready(function () {
    'use strict';
    
    $("#<?php echo $this->id; ?>").fileupload({
        <?php foreach($this->options as $name => $value): ?>
        <?php if($name!='' && $value!=''): ?>
        <?php echo $name; ?>: <?php echo $value; ?>,
        <?php endif; ?>
        <?php endforeach; ?>
    });
    
    $('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
        e.preventDefault();
        $('<iframe style="display:none;"></iframe>')
            .prop('src', this.href)
            .appendTo('body');
    });
    
});
</script>