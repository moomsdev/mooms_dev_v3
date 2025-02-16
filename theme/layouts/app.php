<?php
/**
 * Base app layout.
 *
 * @link    https://docs.wpemerge.com/#/framework/views/layouts
 *
 * @package WPEmergeTheme
 */

if (empty($_GET['_pjax'])) {
    WPEmerge\render('header');
} else {
    echo '<title>';
    wp_title();
    echo '</title>';
}
?>

    <main id="main_content">
        <?php WPEmerge\layout_content(); ?>
    </main>

<?php if (empty($_GET['_pjax'])) {
    WPEmerge\render('footer');
}
?>
