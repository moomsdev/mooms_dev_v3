<?php

use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Insert an array at a specific position
 */
function insertArrayAtPosition($array, $insert, $position)
{
    return array_slice($array, 0, $position, true) + $insert + array_slice($array, $position, null, true);
}

/**
 * Get current language code
 */
function currentLanguage()
{
    return defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';
}

function adminAsset($path)
{
    return get_stylesheet_directory_uri() . '/../resources/admin/' . $path;
}

/**
 * Update or delete post meta
 */
function updatePostMeta($post_id, $field_name, $value = '')
{
    if (empty($value)) {
        return delete_post_meta($post_id, $field_name);
    }

    return update_post_meta($post_id, $field_name, $value) ?: add_post_meta($post_id, $field_name, $value);
}

/**
 * Update or delete user meta
 */
function updateUserMeta($idUser, $key, $value)
{
    if (empty($value)) {
        return delete_user_meta($idUser, $key);
    }

    return update_user_meta($idUser, $key, $value) ?: add_user_meta($idUser, $key, $value);
}

function updateAttachmentSize($attachment_id, $fileName, $width, $height, $type)
{
    $metadata = wp_get_attachment_metadata($attachment_id);
    if (is_array($metadata) && array_key_exists('sizes', $metadata)) {
        $size = $metadata['sizes'];
        $sizeName = $width . 'x' . $height;
        if (!array_key_exists($sizeName, $size)) {
            $metadata['sizes'][$sizeName] = [
                'file' => $fileName,
                'width' => $width,
                'height' => $height,
                'mime-type' => $type,
            ];
        }
        wp_update_attachment_metadata($attachment_id, $metadata);
    }
}

/**
 * Resize image using Intervention Image
 */
function resizeImage($srcPath, $destinationPath, $maxWidth, $maxHeight, $type = 'webp')
{
    try {
        $image = Image::make($srcPath);
        if ($maxWidth || $maxHeight) {
            $image->fit($maxWidth, $maxHeight, static function ($constraint) {
                $constraint->upsize();
            });
        }
        $image->encode($type)->save($destinationPath, 85);
    } catch (\Exception $ex) {
        error_log($ex->getMessage());
    }
}

/**
 * Normalize path for cross-platform compatibility
 */
function crb_normalize_path($path)
{
    return preg_replace('~[/' . preg_quote('\\', '~') . ']~', DIRECTORY_SEPARATOR, $path);
}

/**
 * Truncate text
 */
function subString($str, $limit)
{
    return wp_trim_words($str, $limit, '...');
}

/**
 * Enqueue CSS and JS files
 */
function loadStyles($files = [])
{
    add_action('wp_enqueue_scripts', function () use ($files) {
        $theme_version = wp_get_theme()->get('Version');
        foreach ($files as $index => $file) {
            wp_enqueue_style('theme-css-' . $index, $file, [], $theme_version);
        }
        wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/style.css', [], $theme_version);
    });
}

function loadScripts($files = [])
{
    add_action('wp_enqueue_scripts', function () use ($files) {
        $theme_version = wp_get_theme()->get('Version');
        foreach ($files as $index => $file) {
            wp_enqueue_script('theme-js-' . $index, $file, [], $theme_version, true);
        }
    });
}

/**
 * Get related posts
 */
function getRelatePosts($postId = null, $postCount = null)
{
    global $post;
    $postCount = $postCount ?: get_option('posts_per_page');
    $thisPost = $postId ? get_post($postId) : $post;

    $taxonomies = get_post_taxonomies($thisPost->ID);
    $arrTaxQuery = ['relation' => 'OR'];
    foreach ($taxonomies as $taxonomy) {
        $terms = get_the_terms($thisPost->ID, $taxonomy);
        if ($terms) {
            $arrTaxQuery[] = [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => wp_list_pluck($terms, 'term_id'),
            ];
        }
    }

    return new WP_Query([
        'post_type' => $thisPost->post_type,
        'post_status' => 'publish',
        'posts_per_page' => $postCount,
        'post__not_in' => [$thisPost->ID],
        'tax_query' => $arrTaxQuery,
    ]);
}

/**
 * Get latest or top-viewed posts
 */
function getLatestPosts($postType = 'post', $postCount = null)
{
    return new WP_Query([
        'post_type' => $postType,
        'post_status' => 'publish',
        'posts_per_page' => $postCount ?: get_option('posts_per_page'),
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
}

function getTopViewPosts($postType = 'post', $postCount = null)
{
    return new WP_Query([
        'post_type' => $postType,
        'post_status' => 'publish',
        'posts_per_page' => $postCount ?: get_option('posts_per_page'),
        'meta_key' => '_gm_view_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ]);
}

/**
 * Get human-readable time difference
 */
function formatHumanTime($time)
{
    $diff = Carbon::now()->diffInSeconds(Carbon::parse($time));
    if ($diff < 60)
        return __('Vừa mới đây', 'gaumap');
    if ($diff < 3600)
        return sprintf(__('Khoảng %d phút trước', 'gaumap'), round($diff / 60));
    if ($diff < 86400)
        return sprintf(__('Khoảng %d giờ trước', 'gaumap'), round($diff / 3600));
    if ($diff < 604800)
        return sprintf(__('Khoảng %d ngày trước', 'gaumap'), round($diff / 86400));
    return sprintf(__('Khoảng %d tuần trước', 'gaumap'), round($diff / 604800));
}

/**
 * Get and resize image url by attachment id without add_image_size
 *
 * @param int  $attachment_id
 * @param null $width
 * @param null $height
 *
 * @return false|string
 */
// function getImageUrlById($attachment_id, $width = null, $height = null)
// {
//     if ($width === null && $height === null) {
//     	return wp_get_attachment_image_url($attachment_id, 'full');
//     }

//     $width               = $width ? absint($width) : 0;
//     $height              = $height ? absint($height) : 0;
//     $upload_dir          = wp_upload_dir();
//     $attachment_realpath = crb_normalize_path(get_attached_file($attachment_id));

//     // Neu khong tim thay anh thi return lai placeholder de tranh bi loi
//     if (empty($attachment_realpath)) {
//         return "https://via.placeholder.com/{$width}x{$height}";
//     }

//     $filename  = basename($attachment_realpath);
//     $fileParts = explode('.', $filename);

//     // Kiem tra neu la nhung file anh dac biet nhu gif, svg thi khong xu ly
//     $fileExt = $fileParts[count($fileParts) - 1];
//     if (in_array($fileExt, ['gif', 'svg'])) {
//         return wp_get_attachment_image_url($attachment_id, 'full');
//     }

//     // Kiem tra neu khach hang dang chon default hoac neu thiet bi su dung la iPhone hoac trinh duyet la Safari
//     // $agent = new Agent();
//     // if (get_option('_use_image_ext') === 'default' || $agent->is('iPhone')) {
//     //     $extension = explode('.', $filename)[1];
//     // } else {
//     //     $extension = get_option('_fixed_image_ext');
//     // }

//     $filename = preg_replace('/(\.[^\.]+)$/', '-' . $width . 'x' . $height, $filename) . '.' . $extension;
//     $filepath = crb_normalize_path($upload_dir['basedir'] . '/' . $filename);
//     $url      = trailingslashit($upload_dir['baseurl']) . $filename;

//     // Kiểm tra xem có ảnh đã resize hay chưa, nếu chưa có thì thực hiện resize
//     if (!file_exists($filepath)) {
//         resizeImage($attachment_realpath, $filepath, $width, $height, $extension);
//         // Bổ sung vào metadata để sau này khi user xóa ảnh thì xóa luôn cả ảnh resize
//         updateAttachmentSize($attachment_id, $filename, $width, $height, $extension);
//     }

//     return $url;
// }
function getImageUrlById($attachment_id, $width = null, $height = null)
{
    if ($width === null && $height === null) {
        return wp_get_attachment_image_url($attachment_id, 'full');
    }

    $width = $width ? absint($width) : 0;
    $height = $height ? absint($height) : 0;
    $upload_dir = wp_upload_dir();
    $attachment_realpath = crb_normalize_path(get_attached_file($attachment_id));

    if (empty($attachment_realpath)) {
        return "https://via.placeholder.com/{$width}x{$height}";
    }

    $filename = basename($attachment_realpath);
    $fileParts = explode('.', $filename);
    $fileExt = $fileParts[count($fileParts) - 1];
    if (in_array($fileExt, ['gif', 'svg'])) {
        return wp_get_attachment_image_url($attachment_id, 'full');
    }

    $filename = preg_replace('/(\.[^\.]+)$/', '-' . $width . 'x' . $height, $filename);
    $filepath = crb_normalize_path($upload_dir['basedir'] . '/' . $filename);
    $url = trailingslashit($upload_dir['baseurl']) . $filename;

    // If the resized image does not exist, fall back to the full image
    if (!file_exists($filepath)) {
        return wp_get_attachment_image_url($attachment_id, 'full');
    }

    return $url;
}

/**
 * Resize image by image's url without add_image_size
 *
 * @param      $url
 * @param null $width
 * @param null $height
 * @param bool $crop
 * @param bool $retina
 *
 * @return array|\WP_Error
 */
function resizeImageFly($url, $width = null, $height = null, $crop = true, $retina = false)
{
    global $wpdb;
    if (empty($url)) {
        return new WP_Error('no_image_url', __('No image URL has been entered.', 'wta'), $url);
    }
    // Get default size from database
    $width = $width ?: get_option('thumbnail_size_w');
    $height = $height ?: get_option('thumbnail_size_h');
    // Allow for different retina sizes
    $retina = $retina ? ($retina === true ? 2 : $retina) : 1;
    // Get the image file path
    $file_path = parse_url($url);
    $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
    // Check for Multisite
    if (is_multisite()) {
        global $blog_id;
        $blog_details = get_blog_details($blog_id);
        $file_path = str_replace($blog_details->path . 'files/', '/wp-content/blogs.dir/' . $blog_id . '/files/', $file_path);
    }
    // Destination width and height variables
    $dest_width = $width * $retina;
    $dest_height = $height * $retina;
    // File name suffix (appended to original file name)
    $suffix = "{$dest_width}x{$dest_height}";
    // Some additional info about the image
    $info = pathinfo($file_path);
    $dir = $info['dirname'];
    $ext = $info['extension'];
    $name = wp_basename($file_path, ".$ext");
    if ('bmp' === $ext) {
        return new WP_Error('bmp_mime_type', __('Image is BMP. Please use either JPG or PNG.', 'wta'), $url);
    }
    // Suffix applied to filename
    $suffix = "{$dest_width}x{$dest_height}";
    // Get the destination file name
    $dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
    if (!file_exists($dest_file_name)) {
        /*
         *  Bail if this image isn't in the Media Library.
         *  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
         */
        $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE guid='%s'", $url);
        $get_attachment = $wpdb->get_results($query);
        if (!$get_attachment) {
            return ['url' => $url, 'width' => $width, 'height' => $height];
        }
        // Load Wordpress Image Editor
        $editor = wp_get_image_editor($file_path);
        if (is_wp_error($editor)) {
            return ['url' => $url, 'width' => $width, 'height' => $height];
        }
        // Get the original image size
        $size = $editor->get_size();
        $orig_width = $size['width'];
        $orig_height = $size['height'];
        $src_x = $src_y = 0;
        $src_w = $orig_width;
        $src_h = $orig_height;
        if ($crop) {
            $cmp_x = $orig_width / $dest_width;
            $cmp_y = $orig_height / $dest_height;
            // Calculate x or y coordinate, and width or height of source
            if ($cmp_x > $cmp_y) {
                $src_w = round($orig_width / $cmp_x * $cmp_y);
                $src_x = round(($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
            } else {
                if ($cmp_y > $cmp_x) {
                    $src_h = round($orig_height / $cmp_y * $cmp_x);
                    $src_y = round(($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
                }
            }
        }
        // Time to crop the image!
        $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);
        // Now let's save the image
        $saved = $editor->save($dest_file_name);
        // Get resized image information
        $resized_url = str_replace(basename($url), basename($saved['path']), $url);
        $resized_width = $saved['width'];
        $resized_height = $saved['height'];
        $resized_type = $saved['mime-type'];
        // Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
        $metadata = wp_get_attachment_metadata($get_attachment[0]->ID);
        if (isset($metadata['image_meta'])) {
            $metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
            wp_update_attachment_metadata($get_attachment[0]->ID, $metadata);
        }
        // Create the image array
        $image_array = [
            'url' => $resized_url,
            'width' => $resized_width,
            'height' => $resized_height,
            'type' => $resized_type,
        ];
    } else {
        $image_array = [
            'url' => str_replace(basename($url), basename($dest_file_name), $url),
            'width' => $dest_width,
            'height' => $dest_height,
            'type' => $ext,
        ];
    }
    // Return image array
    return $image_array;
}

/**
 * Dequeue unnecessary scripts
 */
function contactform_dequeue_scripts()
{
    if (!is_singular() || !has_shortcode(get_post()->post_content, 'contact-form-7')) {
        wp_dequeue_script('contact-form-7');
        wp_dequeue_script('google-recaptcha');
        wp_dequeue_style('contact-form-7');
    }
}
add_action('wp_enqueue_scripts', 'contactform_dequeue_scripts', 99);

/**
 * Remove unnecessary admin menus
 */
add_action('admin_init', function () {
    remove_menu_page('edit-comments.php');
    remove_menu_page('edit.php');
});

/**
 * Remove jQuery Migrate
 */
add_action('wp_default_scripts', function ($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
});

// Get video
function getYoutubeEmbedUrl($url)
{
    $youtube_id = '';
    if (preg_match('/(youtube\.com.*(\?v=|\/embed\/|\/v\/|\/.+\/|youtu\.be\/|\/v\/)|\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        $youtube_id = $matches[3];
    }

    if (!empty($youtube_id)) {
        return 'https://www.youtube.com/embed/' . $youtube_id . '?modestbranding=1&showinfo=0&controls=1&frameborder=0&allow=accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture&allowfullscreen';
    }

    return '';
}

function getVideoUrl($video_link)
{
    $video_html = '';

    if (!empty($video_link)) {
        // Handle YouTube URLs
        if (strpos($video_link, 'youtube.com') !== false || strpos($video_link, 'youtu.be') !== false) {
            $youtube_embed_url = getYoutubeEmbedUrl($video_link);
            if (!empty($youtube_embed_url)) {
                $video_html = '<div class="video-embed"><iframe title="YouTube video" src="' . $youtube_embed_url . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }
        }
        // Handle Vimeo URLs
        elseif (strpos($video_link, 'vimeo.com') !== false) {
            $video_ID = substr(parse_url($video_link, PHP_URL_PATH), 1); // Extract the video ID from the URL
            $vimeo_api_url = "https://vimeo.com/api/v2/video/{$video_ID}.json";

            $hash = @file_get_contents($vimeo_api_url);
            if ($hash) {
                $hash_data = json_decode($hash);
                if (isset($hash_data[0])) {
                    $title = $hash_data[0]->title;
                    $video_html = '<div class="video-embed"><iframe title="Video: ' . $title . '" src="https://player.vimeo.com/video/' . $video_ID . '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div>';
                }
            }
        }
    }

    return $video_html;
}

add_post_type_support('page', 'excerpt');

/**
 * get all page
 */
function getListAllPages()
{
    $pages = get_posts([
        'post_type' => 'page',
        'posts_per_page' => -1,
        'lang' => get_icl_language_code(),
    ]);

    $list = [];
    foreach ($pages as $page) {
        $list[$page->ID] = $page->post_title;
    }

    return $list;
}
