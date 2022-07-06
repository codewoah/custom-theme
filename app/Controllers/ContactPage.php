<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class ContactPage extends Controller
{
    public static function title()
    {
        return get_the_title();
    }
    
    public static function titleDescription()
    {
        return get_field('title_description');
    }

    public static function pageType()
    {
        return get_field('contact_page_type');
    }

    public static function contactFormField()
    {
        $contact_form = get_field('contact_form');
        return array (
            "contactFormId" =>  $contact_form[0]->ID,
            "contactFormTitle" => $contact_form[0]->post_title,
            "contactFormShortcode" => '[contact-form-7 id="'.$contact_form[0]->ID.'" title="'.$contact_form[0]->post_title.'"]'
        );
    }

    public static function contactExhibitionsFields()
    {
        $exhibitions_section_title = get_field('exhibitions_section')["exhibitions_section_title"];
        $exhibitions_image = get_field('exhibitions_section')["exhibitions_image"];
        $exhibitions_list = get_field('exhibitions_section')["exhibitions_list"];
        $exhibitions_section_title=str_ireplace('<p>','',$exhibitions_section_title);
        $exhibitions_section_title=str_ireplace('</p>','',$exhibitions_section_title);
        return array (
            "exhibitions_section_title" =>  $exhibitions_section_title,
            "exhibitions_image" => $exhibitions_image,
            "exhibitions_list" => $exhibitions_list,
        );
    }
}
