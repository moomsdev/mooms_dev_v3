<?php
//Template name: Liên hệ

/**
 * App Layout: layouts/app.php
 *
 * This is the template that is used for displaying 404 errors.
 *
 * @package WPEmergeTheme
 */
$banner = getPostThumbnailUrl(get_the_ID());
?>
<section class="breadcumb" aria-label="breadcumb" style="background-image: url('<?php echo getImageAsset('/img/breadcumb.jpg') ?>');">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="main">
					<div class="bread">
						<div class="img-icon">
							<img src="img/breadcumb-icon.png" alt="#">
						</div>
						<div class="bread-title"><?= get_the_title(); ?></div>
						<div class="bread-subtitle">
							<!-- <a href="index.html">Home</a> 
              <span class="spacebread"></span> 
            <span>Contact</span> -->
							<?= get_template_part('template-parts/breadcrumb') ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- section contact -->
<section aria-label="contact">
	<div class="container-fluid">
		<div class="row p-3-vh">

			<div class="col-md-6">
				<div class="text-side">
					<h3 class="heading">Talk with us </h3>
					<div class="content">
						<?php the_content(); ?>
					</div>
					<div class="address">
						<div class="heading">Our Office</div>
						<div class="list">
							<i class="fa fa-map-marker"></i>
							<?= getOption('address'); ?>
						</div>
						<div class="list">
							<i class="fa fa-envelope-o"></i>
							<a href="mailto:<?= getOption('email'); ?>" target="_blank" rel="noopener noreferrer"><?= getOption('email'); ?></a>
						</div>
						<div class="list">
							<i class="fa fa-phone"></i><?= getOption('phone_number'); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<form id="form-contact1" class="autoheight">
					<div class="form-group user-name">
						<input type="text" class="form-control" required id="name-contact-1" placeholder="Your Name">
					</div>

					<div class="form-group user-email">
						<input type="email" class="form-control" required id="email-contact" placeholder="Your Email">
					</div>

					<div class="form-group user-message">
						<textarea class="form-control" required id="message-contact" placeholder="Your Message"></textarea>
						<div class="success" id="mail_success">Thank you. Your message has been sent</div>
						<div class="error" id="mail_failed">Error, email not sent</div>
					</div>
					<button type="submit" id="send-contact-1" class="btn-contact">Send Now</button>
				</form>
			</div>
		</div>
	</div>
</section>
<!-- section end -->