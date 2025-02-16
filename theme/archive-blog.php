<?php
/**
 * App Layout: layouts/app.php
 *
 * This is the template that is used for displaying all posts by default.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPEmergeTheme
 */
?>
<div class="page-listing">
	<div class="mm-container">
		<h1 class="title-block"><?php thePageTitle(); ?></h1>

		<div class="list-items">
			<?php
			if (have_posts()) :
				while (have_posts()) : the_post();
					get_template_part('template-parts/loop','post');
				endwhile;
				wp_reset_postdata();
			endif;
			thePagination();
			?>
		</div>
	</div>
</div>


