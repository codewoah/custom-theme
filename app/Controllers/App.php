<?php

namespace App\Controllers;
use Sober\Controller\Controller;

class App extends Controller
{
    protected $acf = true;
    public function siteName()
    {
        return get_bloginfo('name');
    }
    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);

            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }
/*
 * Change les caractéristiques et données des balises des menus
 */
    public function headerMenuMobile() {
        return array(
            'theme_location'  => 'header-mobile',
            'menu'            => 'header-mobile',
            'container'       => 'nav',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'main-header__menu__items',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => '',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
            'item_spacing'    => 'preserve',
            'depth'           => 0,
            // 'walker'          => new \MenusWalker\MainMenus()
        );
    }
    public function headerMenu() {
        return array(
            'theme_location'  => 'header',
            'menu'            => 'header',
            'container'       => 'nav',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'main-header__menu__items',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => '',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
            'item_spacing'    => 'preserve',
            'depth'           => 0,
            'walker'          => new \MenusWalker\MainMenus()
        );
    }
    public function footerMenu() {
        return array(
            'theme_location'  => 'footer',
            'menu'            => 'footer',
            'container'       => 'div',
            'container_class' => 'main-footer__site',
            'container_id'    => '',
            'menu_class'      => 'main-footer__site__list',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => '',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
            'item_spacing'    => 'preserve',
            'depth'           => 0,
            'walker'          => new \MenusWalker\MainMenus()
        );
    }
}

