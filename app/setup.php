<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('willo/main.css', asset_path('main-css.css'), false, null);
    wp_enqueue_script('willo/runtime.js', asset_path('runtime.js'), [], null, false);
    wp_enqueue_script('willo/main.js', asset_path('main-js.js'), [], null, false);
    wp_localize_script( 'willo/main.js', 'willo_frontend',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'checkout_url' => wc_get_checkout_url(),
            'my_account_url' => wc_get_account_endpoint_url('orders'),
            'home_url' => home_url('/'),
            'essential_set_price' => wc_get_product(sage('config')->get('theme')['side_product_add_cart']['essential_set'])->get_price(),
            'success_size_updated' => array(
                'title' => __('Thank you for the update!','willo'),
                'icon' => asset_path('images/benefit-deliver.svg'),
                'text' => sprintf( __('We’ve just received your size update and get your order prepared right away. If you have a question, reach us at <a href="mailto:%s">%s</a>'), 'support@willo.com', 'support@willo.com  ' )
            )
        )
    );

//    wp_dequeue_style( 'select2' );
    wp_deregister_style( 'select2' );

//    if (is_single() && comments_open() && get_option('thread_comments')) {
//        wp_enqueue_script('comment-reply');
//    }
}, 20);



/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus( array(
        'header' => __('header', 'sage'),
        'header-mobile' => __('header-mobile', 'sage'),
        'footer' => __('footer', 'sage')
    ) );

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
//    add_editor_style(asset_path('styles/main.css'));

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});


add_action( 'init', function(){
    add_rewrite_endpoint( 'order-update-mouthpiece-size', EP_PAGES  );
    add_rewrite_endpoint( 'modify-subscription-plan', EP_PAGES  );
    add_rewrite_endpoint( 'edit-subscription-plan', EP_PAGES  );
    add_rewrite_endpoint( 'edit-address-billing', EP_PAGES  );
    add_rewrite_endpoint( 'edit-address-shipping', EP_PAGES  );

    register_post_status( 'wc-shipped', array(
        'label' => _x( 'Shipped', 'WooCommerce Order status', 'text_domain' ),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Approved (%s)', 'Approved (%s)', 'text_domain' )
    ) );

    register_post_status( 'wc-out-delivery', array(
        'label' => _x( 'Out for delivery', 'WooCommerce Order status', 'text_domain' ),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Approved (%s)', 'Approved (%s)', 'text_domain' )
    ) );

    register_post_status( 'wc-delivered', array(
        'label' => _x( 'Delivered', 'WooCommerce Order status', 'text_domain' ),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Approved (%s)', 'Approved (%s)', 'text_domain' )
    ) );

});

add_action('acf/init', function () {
    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

    // Register options page.
    acf_add_options_page(array(
        'page_title' => __('Theme General Settings'),
        'menu_title' => __('Theme Settings'),
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));

        acf_add_options_page(array(
            'page_title' => __('Emails configuration'),
            'menu_title' => __('Emails options'),
            'menu_slug' => 'emails-config-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'icon_url' => 'dashicons-email'
        ));
}
});


add_action('wp_head', function () {
    echo get_field('gtm_script','option');
});
