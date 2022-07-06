<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || (is_page() && !is_front_page())) {
        if (!in_array(basename(get_permalink()), $classes, true)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    if (!isset($_COOKIE['willo_pionner_banner_closed'])) {
        $classes[] = 'has-banner';
    }

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment', 'embed'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    collect(['get_header', 'wp_head'])->each(function ($tag) {
        ob_start();
        do_action($tag);
        $output = ob_get_clean();
        remove_all_actions($tag);
        add_action($tag, function () use ($output) {
            echo $output;
        });
    });
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory() . '/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Render comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );

    $data = collect(get_body_class())->reduce(function ($data, $class) use ($comments_template) {
        return apply_filters("sage/template/{$class}/data", $data, $comments_template);
    }, []);

    $theme_template = locate_template(["views/{$comments_template}", $comments_template]);

    if ($theme_template) {
        echo template($theme_template, $data);
        return get_stylesheet_directory() . '/index.php';
    }

    return $comments_template;
}, 100);

add_filter('willo_sumary_detail_product', function ($text, $product) {
    $sub_parent_product = 13;
    if (\WC_Subscriptions_Product::is_subscription($product)) {
        $period = \WC_Subscriptions_Product::get_period($product);
        if ($period === 'year') {
            $text = sprintf(__('<div>Annual subscription</div>'));
        } else {
            $text = sprintf(__('<div>Monthly subscription</div>'));
        }
        $text .= get_field('product_order_summary_details', $sub_parent_product);
    }


    return $text;
}, 10, 2);

add_filter('show_admin_bar', '__return_false');

/**
 * Put ACF fields in /acf-json file at theme's root
 */
// add_filter('acf/settings/save_json', function () {
//     return dirname(__DIR__) . '/acf-json';
// });

// /**
//  * Load ACF fields in /acf-json file at theme's root
//  */
// add_filter('acf/settings/load_json', function ($paths) {
//     // remove original path
//     unset($paths[0]);
//     // append path
//     $paths[] = dirname(__DIR__) . '/acf-json';
//     return $paths;
// });
