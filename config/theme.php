<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme Directory
    |--------------------------------------------------------------------------
    |
    | This is the absolute path to your theme directory.
    |
    | Example:
    |   /srv/www/example.com/current/web/app/themes/sage
    |
    */

    'dir' => get_theme_file_path(),

    /*
    |--------------------------------------------------------------------------
    | Theme Directory URI
    |--------------------------------------------------------------------------
    |
    | This is the web server URI to your theme directory.
    |
    | Example:
    |   https://example.com/app/themes/sage
    |
    */

    'uri' => get_theme_file_uri(),

    'launch_date' => 1593095132,

    'side_product_add_cart' => [
        'fit_finder_kit' => get_field('fit_finder_kit_product','option'),
        'essential_set' => get_field('essential_set_product','option')
    ],

    'default_plan' => [
        'Year' => get_field('default_plan_year','option'),
        'Month' => get_field('default_plan_month','option')
    ],


    'fresh_routine_parent_variation' => get_field('fresh_routine_parent_variation','option'),

    'plans' => [
        'solo' => array(
            'plan' => '1',
            'qty' => 1,
            'select_ui' => false
        ),
        'duo' => array(
            'plan' => '2',
            'qty' => 2,
            'remise' => '5%',
            'select_ui' => false
        ),
        'Family of 3' => array(
            'plan' => '3',
            'qty' => 3,
            'remise' => '9%',
            'select_ui' => true
        ),
        'Family of 4' => array(
            'plan' => '4',
            'qty' => 4,
            'remise' => '14%',
            'select_ui' => true
        ),
        'Family of 5' => array(
            'plan' => '5',
            'qty' => 5,
            'remise' => '20%',
            'select_ui' => true
        ),
    ],

    'klaviyo' => [
        'api_key' => 'pk_a6d6dde87c39934bc2840450fd53e23d54',
        'newsletter_list_id' => 'UwGiiG',
        'newsletter_list_id_pro' => 'Q7L9hc',
    ]
];
