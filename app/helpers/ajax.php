<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hàm ajax update custom sort order
 */
add_action('wp_ajax_update_custom_sort_order', 'updateCustomSortOrder');
function updateCustomSortOrder() {
    if (empty($_POST['post_ids']) || empty($_POST['current_page'])) {
        wp_send_json_error(['message' => 'Missing parameters.']);
    }

    $postIds = array_map('absint', $_POST['post_ids']);
    $currentPage = absint($_POST['current_page']);
    $order = (($currentPage - 1) * count($postIds)) + 1;

    foreach ($postIds as $postId) {
        wp_update_post([
            'ID'         => $postId,
            'menu_order' => $order,
        ]);
        $order++;
    }

    wp_send_json_success();
}
 
/**
 * Hàm ajax update post thumbnail id
 */
add_action('wp_ajax_nopriv_update_post_thumbnail_id', 'updatePostThumbnailId');
add_action('wp_ajax_update_post_thumbnail_id', 'updatePostThumbnailId');

function updatePostThumbnailId() {
    // Kiểm tra các tham số post_id và attachment_id
    if (empty($_POST['post_id']) || empty($_POST['attachment_id'])) {
        wp_send_json_error(['message' => 'Missing parameters.']);
    }

    $postId = absint($_POST['post_id']);
    $attachmentId = absint($_POST['attachment_id']);

    // Cập nhật _thumbnail_id bằng hàm update_post_meta
    if (update_post_meta($postId, '_thumbnail_id', $attachmentId)) {
        wp_send_json_success(['message' => 'Thumbnail updated.']);
    } else {
        wp_send_json_error(['message' => 'Failed to update thumbnail.']);
    }
}


/**
 * Hàm ajax send form liên hệ
 */
add_action('wp_ajax_nopriv_send_contact_form', 'sendContactForm');
add_action('wp_ajax_send_contact_form', 'sendContactForm');
// function sendContactForm() {
//     if (!check_ajax_referer('send_contact_form', '_token', false)) {
//         wp_send_json_error(['message' => __('Token mistake.')]);
//     }

//     $blogName = get_bloginfo('name');
//     $blogUrl = get_bloginfo('url');

//     $html = sprintf(
//         '<p>Send from: %s (%s)</p><p>Contact phone number: %s</p><p>Subject: %s</p><p>Contact message:</p><p>%s</p>',
//         esc_html($_POST['name']),
//         sanitize_email($_POST['email']),
//         esc_html($_POST['phone_number']),
//         esc_html($_POST['subject']),
//         esc_html($_POST['message'])
//     );

//     $headers = [
//         'Content-Type: text/html; charset=UTF-8',
//         'Reply-To: ' . esc_html($_POST['name']) . ' <' . sanitize_email($_POST['email']) . '>',
//     ];

//     $success = wp_mail(get_option('admin_email'), $blogName . ': ' . esc_html($_POST['subject']), $html, $headers);

//     if ($success) {
//         wp_send_json_success(['message' => __('Yêu cầu của bạn đã được hệ thống ghi nhận.', 'gaumap')]);
//     }

//     wp_send_json_error(['message' => __('Đã có lỗi xảy ra, xin vui lòng thử lại.', 'gaumap')]);
// }
function sendContactForm() {
    // Kiểm tra nonce để bảo mật
    if (!check_ajax_referer('send_contact_form', '_token', false)) {
        wp_send_json_error(['message' => __('Token mistake.')]);
    }

    // Kiểm tra các trường bắt buộc
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['phone_number']) || empty($_POST['message'])) {
        wp_send_json_error(['message' => __('Please fill in all required fields.', 'gaumap')]);
    }

    // Lấy thông tin từ form
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Lấy thông tin blog
    $blogName = get_bloginfo('name');
    $blogUrl = get_bloginfo('url');

    // Nội dung email
    $html = sprintf(
        '<p>Send from: %s %s (%s)</p><p>Contact phone number: %s</p><p>Contact message:</p><p>%s</p>',
        esc_html($first_name),
        esc_html($last_name),
        esc_html($email),
        esc_html($phone_number),
        esc_html($message)
    );

    // Thiết lập header
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . esc_html($first_name . ' ' . $last_name) . ' <' . sanitize_email($email) . '>',
    ];

    // Gửi email đến quản trị viên
    $success = wp_mail(get_option('admin_email'), $blogName . ': New Contact Form Submission', $html, $headers);

    // Kiểm tra kết quả gửi email và phản hồi JSON
    if ($success) {
        wp_send_json_success(['message' => __('Your request has been successfully submitted.', 'gaumap')]);
    } else {
        // Ghi lại log nếu gửi email thất bại
        error_log('Email failed to send.');
        wp_send_json_error(['message' => __('An error occurred. Please try again later.', 'gaumap')]);
    }
}


/**
 * Hàm ajax load page content
 */
add_action('wp_ajax_nopriv_get_page', 'ajaxGetPage');
add_action('wp_ajax_get_page', 'ajaxGetPage');
function ajaxGetPage() {
    ob_start();
    get_template_part('page');
    $content = ob_get_clean();
    wp_send_json_success($content);
}
