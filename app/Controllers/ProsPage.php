<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class ProsPage extends Controller
{
    public static function title()
    {
        return get_the_title();
    }
    
    public static function titleDescription()
    {
        return get_field('title_description');
    }

    public static function getLayouts()
    {
        //acf flexible content field
        $layouts = get_field('pros_sections');
        //this will hold all the layouts
        $data = [];
        //go through each layouts that got put in there
        if($layouts):
            foreach($layouts as $layout):
                //$this_layout is the current one
                $this_layout = (object)[$layout];

                //put the current object into the $data array
                array_push($data, $this_layout);

            endforeach;
            //return the $data array with all the flexible content objects in it
            return $data;
        endif;
    }
}
