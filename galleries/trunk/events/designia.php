<?php

class GalleriesDesigniaPreload
{
    public function eventDesigniaGetNavItems(){
        echo '<li class=nav_item>
                    <a href="'.XOOPS_URL.'/modules/galleries/admin/">
                        <img src="'.RMCURL.'/themes/designia/images/gals.png" alt="'.__('Galleries','galleries').'" />
                        <p>'.__('Galleries','galleries').'</p>
                    </a>
                </li>';
    }
}