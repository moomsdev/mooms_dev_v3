<?php
use WPEmerge\Facades\WPEmerge;
use WPEmergeTheme\Facades\Theme;

if (!defined('ABSPATH')) {
    exit;
}

define('ALLOW_UNFILTERED_UPLOADS', true);

define('AUTHOR', [
    'name'           => 'LA CÀ DEV',
    'email'          => 'support@mooms.dev',
    'phone_number'   => '0989 64 67 66',
    'website'        => 'https://mooms.dev/',
    'date_started'   => get_option('_theme_info_date_started'),
    'date_published' => get_option('_theme_info_date_publish'),
]);

define('SUPER_USER', ['mooms_dev']);

/**
 * Constant definitions.
 */
define('APP_APP_DIR_NAME', 'app');
define('APP_APP_HELPERS_DIR_NAME', 'helpers');
define('APP_APP_ROUTES_DIR_NAME', 'routes');
define('APP_APP_SETUP_DIR_NAME', 'setup');
define('APP_DIST_DIR_NAME', 'dist');
define('APP_RESOURCES_DIR_NAME', 'resources');
define('APP_THEME_DIR_NAME', 'theme');
define('APP_VENDOR_DIR_NAME', 'vendor');
define('APP_THEME_USER_NAME', 'users');
define('APP_THEME_ECOMMERCE_NAME', 'users');
define('APP_THEME_POST_TYPE_NAME', 'post-types');
define('APP_THEME_TAXONOMY_NAME', 'taxonomies');
define('APP_THEME_WIDGET_NAME', 'widgets');
define('APP_THEME_BLOCK_NAME', 'blocks');
define('APP_THEME_WALKER_NAME', 'walkers');
define('APP_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_APP_DIR', APP_DIR . APP_APP_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_HELPERS_DIR', APP_APP_DIR . APP_APP_HELPERS_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_ROUTES_DIR', APP_APP_DIR . APP_APP_ROUTES_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_RESOURCES_DIR', APP_DIR . APP_RESOURCES_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_THEME_DIR', APP_DIR . APP_THEME_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_VENDOR_DIR', APP_DIR . APP_VENDOR_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_DIST_DIR', APP_DIR . APP_DIST_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_ECOMMERCE_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_ECOMMERCE_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_USER_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_USER_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_POST_TYPE_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_POST_TYPE_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_TAXONOMY_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_TAXONOMY_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_WIDGET_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_WIDGET_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_BLOCK_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_BLOCK_NAME . DIRECTORY_SEPARATOR);
define('APP_APP_SETUP_WALKER_DIR', APP_THEME_DIR . APP_APP_SETUP_DIR_NAME . DIRECTORY_SEPARATOR . APP_THEME_WALKER_NAME . DIRECTORY_SEPARATOR);

/**
 * Load composer dependencies.
 */
if (file_exists(APP_VENDOR_DIR . 'autoload.php')) {
    require_once APP_VENDOR_DIR . 'autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
}

/**
 * Enable the global Theme:: shortcut so we don't have to type WPEmergeTheme:: every time.
 */
WPEmerge::alias('Theme', \WPEmergeTheme\Facades\Theme::class);

/**
 * Load helpers.
 */
require_once APP_APP_DIR . 'helpers.php';

/**
 * Bootstrap Theme after all dependencies and helpers are loaded.
 */
Theme::bootstrap(require APP_APP_DIR . 'config.php');

/**
 * Register hooks.
 */
require_once APP_APP_DIR . 'hooks.php';

add_action('after_setup_theme', function () {
    /**
     * Load textdomain.
     */
    load_theme_textdomain('gaumap', APP_DIR . 'languages');

    /**
     * Register theme support.
     */
    require_once APP_APP_SETUP_DIR . 'theme-support.php';

    /**
     * Register menu locations.
     */
    require_once APP_APP_SETUP_DIR . 'menus.php';

    /**
     * Support ajax functions
     */
    require_once APP_APP_SETUP_DIR . 'ajax.php';

    /**
     * Define guternberg block
     */
    // require_once APP_APP_SETUP_DIR . '/blocks/sample.php'; 

    $blocks_dir = APP_APP_SETUP_DIR . '/blocks'; // Đường dẫn đến thư mục blocks
    $block_files = glob($blocks_dir . '/*.php'); // Lấy tất cả các file PHP trong thư mục

    foreach ($block_files as $block_file) {
        require_once $block_file; // Nạp mỗi file
    }
});

/**
 * Remove emoji
 */
add_action('init', static function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    /**
     * Filter function used to remove the tinymce emoji plugin.
     *
     * @param array $plugins
     *
     * @return array Difference betwen the two arrays
     */
    add_filter('tiny_mce_plugins', static function ($plugins) {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpemoji']);
        } else {
            return [];
        }
    });

    /**
     * Remove emoji CDN hostname from DNS prefetching hints.
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     *
     * @return array Difference betwen the two arrays.
     */
    add_filter('wp_resource_hints', static function ($urls, $relation_type) {
        if ('dns-prefetch' === $relation_type) {
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
            $urls = array_diff($urls, [$emoji_svg_url]);
        }

        return $urls;
    }, 10, 2);
});

/**
 * Autoload all required file
 */
$folders = [
    APP_APP_SETUP_ECOMMERCE_DIR,
    APP_APP_SETUP_POST_TYPE_DIR,
    APP_APP_SETUP_POST_TYPE_DIR,
    APP_APP_SETUP_TAXONOMY_DIR,
    APP_APP_SETUP_WALKER_DIR,
];
foreach ($folders as $folder) {
    $filesPath = scandir($folder);
    if ($filesPath !== false) {
        foreach ($filesPath as $item) {
            $file = $folder . $item;
            if (is_file($file)) {
                require_once $folder . $item;
            }
        }
    }
}

add_filter('style_loader_tag', function ($html, $handle) {
    return str_replace("media='all' />", 'media="all" rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">', $html);
}, 10, 2);

/**
 * sort_dashboard_posts
 */
// add_filter('pre_get_posts', 'sort_dashboard_posts');
// function sort_dashboard_posts($query)
// {
//     if (is_admin() && $query->is_main_query() && $query->get('post_type') != 'page') {
//         $query->set('orderby', 'date');
//         $query->set('order', 'DESC');
//     }
// }

/**
 * Prevent thumbnails from being generated on upload
 */
function remove_all_image_sizes($sizes)
{
    return array();
}
add_filter('intermediate_image_sizes_advanced', 'remove_all_image_sizes');

function tinymce_allow_unsafe_link_target($mceInit)
{
    $mceInit['allow_unsafe_link_target'] = true;
    return $mceInit;
}

new \App\PostTypes\blog();

function my_theme_enqueue_scripts() {
    // Đăng ký script của bạn
    wp_enqueue_script('my-theme-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    // Truyền biến ajaxurl vào script
    wp_localize_script('my-theme-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');
