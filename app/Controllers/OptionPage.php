<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class OptionPage extends Controller
{

    public static function getIconsFields()
    {
        return array(
            "icons_section_title" => get_field('icons_section_title', 'option'),
            "icons_section_subtitle" => get_field('icons_section_subtitle', 'option'),
            "icons_list" => get_field('icons_list', 'option')
        );
    }

    public static function getSocialLinksFields()
    {
        return array(
            "instagram_link" => get_field('instagram_link', 'option'),
            "twitter_link" => get_field('twitter_link', 'option'),
            "facebook_link" => get_field('facebook_link', 'option')
        );
    }

    public static function getSliderFields()
    {
        return get_field('slider_blue_content', 'option');
    }

    public static function getContactFields()
    {
        return get_field('contact_info_section', 'option');
    }


}
