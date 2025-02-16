<?php
global $post;
$postID = $post->ID;
$url = get_the_permalink($postID);
$thumbnail = getPostThumbnailUrl($postID);
$title = get_the_title($postID);
$category = get_the_terms($postID, 'blog_cat');
?>

<div class="col-md-4 mt-5">
	<div class="content-col">
		<div class="img">
			<img src="<?=$thumbnail;?>" alt="<?= $title; ?>" loading="lazy">
			<div class="date">
				<?= get_the_date('Y.m.d', $postID); ?>
			</div>
		</div>
		<div class="heading">
			<?= $title; ?>
		</div>
		<class="desc autoheight">
			<!-- get 30 letters -->
			<?= get_the_excerpt($postID); ?>
		</class=>
		<a class="btn-content" href="<?= $url; ?>">
			<span class="shine"></span>
			View More
		</a>
	</div>
</div>