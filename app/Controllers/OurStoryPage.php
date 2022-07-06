<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class OurStoryPage extends Controller
{
    protected $acf = true;

    public static function getLayouts()
    {
        //acf flexible content field
        $layouts = get_field('ourStory_content');
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
