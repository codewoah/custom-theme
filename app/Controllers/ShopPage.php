<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class ShopPage extends Controller
{

    protected $acf = true;

    public static function getText()
    {
        return get_field('text_content');
    }
}
