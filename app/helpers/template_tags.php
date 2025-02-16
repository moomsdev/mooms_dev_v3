<?php
/**
 * get resources image uri
 *
 * @param string $path
 *
 * @return string
 */
function getImageAsset($path) {
    $my_theme   = wp_get_theme();
    $theme_name = str_replace('/theme', '', $my_theme->get_stylesheet());
    if (carbon_get_theme_option('use_short_url') !== true) {
        $siteUrl = get_template_directory_uri() . "/resources/images/";
    } else {
        $siteUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/img/";
    }

    return $siteUrl . $path;
}

function template($name) {
    get_template_part('templates/' . $name);
}

/**
 * Get theme option dạng image
 *
 * @param string $name
 * @param int    $w
 * @param int    $h
 *
 * @return false|string
 */
function getOptionImageUrl($name, $w, $h) {
    return getImageUrlById(getOption($name), $w, $h);
}

function getFieldImageUrl($name, $w, $h) {
    return getImageUrlById(get_field($name,'option'), $w, $h);
}

function getPostThumbnailUrl($postId, $width = null, $height = null) {
    $defaultImage = getImageUrlById(getOption('hinh_anh_mac_dinh'), $width, $height);
    try {
        $imageId = get_post_thumbnail_id($postId);
        if (empty($imageId)) {
            return $defaultImage;
        }

        if ($width === null && $height === null) {
            return wp_get_attachment_image_url($imageId, 'full');
        }

        return getImageUrlById($imageId, $width, $height);
    } catch (\Exception $ex) {
        return $defaultImage;
    }
}

function getPostMetaImageUrl($name, $id = null, $w = null, $h = null) {
    $id = empty($id) ? get_the_ID() : $id;
    return getImageUrlById(carbon_get_post_meta($id, $name), $w, $h);
}

function getPostMeta($name, $id = null) {
	$id = empty($id) ? get_the_ID() : $id;
	return carbon_get_post_meta($id, $name);
}
function thePostMeta($name) {
    echo getPostMeta($name, get_the_ID());
}

function thePostMetaImageUrl($name = '', $w = null, $h = null) {
    echo getPostMetaImageUrl($name, get_the_ID(), $w, $h);
}

/**
 * Echo view count of post
 *
 * @param null $postId
 */
function getViewCount($postId = null) {
    $postId = empty($postId) ? get_the_ID() : $postId;
    $cache_key = "post_{$postId}_view_count";
    $view_count = get_transient($cache_key);

    if ($view_count === false) {
        $count_key = '_gm_view_count';
        $view_count = get_post_meta($postId, $count_key, true);
        if (empty($view_count)) {
            $view_count = 0;
        }
        set_transient($cache_key, $view_count, 12 * HOUR_IN_SECONDS); // Cache for 12 hours
    }

    return $view_count;
}

function updateViewCount($postId = null) {
	$postId = empty($postId) ? get_the_ID() : $postId;

	$count_key = '_gm_view_count';
	$count     = (int)get_post_meta($postId, $count_key, true);
	if (empty($count)) {
		$count = 1;
		delete_post_meta($postId, $count_key);
		add_post_meta($postId, $count_key, $count);
	} else {
		$count++;
		update_post_meta($postId, $count_key, $count);
	}

	return $count;
}
function theViewCount($postId = null) {
    echo getViewCount($postId);
}

function thePostThumbnailUrl($width = null, $height = null) {
    echo getPostThumbnailUrl(get_the_ID(), $width, $height);
}

function theTitle($limit = 999) {
    echo subString(get_the_title(), $limit);
}

function getExcerpt($postId, $limit) {
	return subString(get_the_excerpt($postId), $limit);
}
function theExcerpt($limit = 9999) {
    echo '<p>' . getExcerpt(get_the_ID(), $limit) . '</p>';
}

function theContent() {
    $content = get_the_content();
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    echo !empty($content) ? wp_kses_post($content) : __('Dữ liệu đang được cập nhật', 'gaumap');
}

function getOption($name) {
	return carbon_get_theme_option($name . currentLanguage());
}
function theOption($name) {
    echo getOption($name);
}

function theOptionImage($name, $width = null, $height = null) {
    $imageId = getOption($name);
    if (!empty($imageId)) {
        echo getImageUrlById($imageId, $width, $height);
    }
}

/**
 * Load resource
 *
 * @param string $path
 */
function theAsset($path) {
    echo getImageAsset($path);
}

/**
 * Tạo phân trang sử dụng Bootstrap 5
 *
 * @param mixed|\WP_Query $query
 */
function thePagination($query = null) {
    if (empty($query)) {
        global $wp_query;
        $query = $wp_query;
    }

    $paged = (get_query_var('paged') === 0) ? 1 : get_query_var('paged');
    $pages = paginate_links([
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $query->max_num_pages,
        'mid_size'  => 2, // Hiển thị 2 trang trước và 2 trang sau trang hiện tại
        'type'      => 'array',
        'prev_next' => true,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ]);
    

    if (is_array($pages)) {
        $pagination = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">'; // Added 'justify-content-center' to center pagination if needed

        foreach ($pages as $page) {
            // Add active class to the current page
            if (strpos($page, 'current') !== false) {
                $pagination .= '<li class="page-item active" aria-current="page"><span class="page-link">' . strip_tags($page) . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            }
        }

        $pagination .= '</ul></nav>';

        echo $pagination;
    }
}


/**
 * Tạo breadcrumb
 */
function theBreadcrumb() {
    get_template_part('part_templates/breadcrumb');
}
function theShareSocials() {
	get_template_part('part_templates/share_box');
}

function getPageTitle() {
    $obj   = get_queried_object();
    $title = get_bloginfo('name');
    if (is_single() || is_page()) {
        $title = get_the_title();
    } elseif (is_search()) {
        /* translators: search results page title */
        $title = sprintf(__('Kết quả tìm kiếm cho từ khóa: %s', 'gaumap'), get_search_query());
    } elseif (is_category()) {
        /* translators: category post listing page title */
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        /* translators: tag post listing page title */
        $title = sprintf(__('Tag: %s', 'gaumap'), single_tag_title('', false));
    } elseif (is_day()) {
        /* translators: day archive post listing page title */
        $title = sprintf(__('Daily Archives: %s', 'gaumap'), get_the_time('F jS, Y'));
    } elseif (is_month()) {
        /* translators: month archive post listing page title */
        $title = sprintf(__('Monthly Archives: %s', 'gaumap'), get_the_time('F, Y'));
    } elseif (is_year()) {
        /* translators: year archive post listing page title */
        $title = sprintf(__('Yearly Archives: %s', 'gaumap'), get_the_time('Y'));
    } elseif (is_author()) {
        /* translators: author archive post listing page title */
        $title = sprintf(__('Posts by %s', 'gaumap'), get_the_author());
    } elseif (class_exists('WooCommerce') && is_woocommerce()) {
        $title = woocommerce_page_title(false);
    } elseif (is_archive()) {
        if ($obj instanceof WP_Term) {
            $title = $obj->name;
        } elseif ($obj instanceof WP_Post_Type) {
            $title = $obj->label;
        }
    } elseif (is_404()) {
        $title = __('Lỗi 404 - Không tìm thấy trang bạn yêu cầu', 'gaumap');
    }
    return $title;
}
function thePageTitle() {
	echo getPageTitle();
}

function theLanguageSwitcher($showName = true, $showFlag = false) {
    if (function_exists('pll_the_languages')) {
        echo '<ul class="language-switcher">';
        pll_the_languages([
            'show_names'    => $showName,
            'show_flags'    => $showFlag,
            'hide_if_empty' => false,
            'hide_current'  => true,
        ]);
        echo '</ul>';
    }
}
