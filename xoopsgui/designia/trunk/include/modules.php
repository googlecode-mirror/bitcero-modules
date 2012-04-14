<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

?>
<div class="customScrollBox mod-wrapper">
    <div class="horWrapper">
        <div class="container">
            <div class="content">
                <ul>
<?php
foreach($modules as $mod){
?>
<li>
    <a href="<?php echo $mod['admin_link']; ?>">
    <span class="image" style="background-image: url(<?php echo $mod['image']; ?>);"><span><?php echo $mod['name']; ?></span></span>
    <span class=name><?php echo $mod['name']; ?></span></a>
</li>
<?php
}
?>
                </ul>
            </div>
        </div>
    </div>
    <div class="dragger_container">
            <div class="dragger"></div>
        </div>
</div>
