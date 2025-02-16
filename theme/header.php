<?php

/**
 * Theme header partial.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPEmergeTheme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
	<script src="https://code.iconify.design/3/3.0.0/iconify.min.js"></script>

</head>

<body <?php body_class(); ?>>
	<?php app_shim_wp_body_open(); ?>
	<div class="wrapper_mm content-wrapper" id="swup">
		
		<header id="header" class="init"><!-- header -->
			<!-- nav -->
			<div class="navbar-default-white navbar-fixed-top">
				<div class="container-fluid"><!-- container -->
					<!-- row -->
					<div class="row p-3-vh">
						<!-- logo -->
						<a class="logo centered" href="<?php bloginfo('url') ?>">
							<img class="h-100 init" alt="<?php bloginfo('url'); ?>" src="<?php theOptionImage('logo'); ?>">
							<img class="h-100 show" alt="<?php bloginfo('url'); ?>" src="<?php theOptionImage('logo'); ?>">
						</a>
						<!-- logo end -->

						<!-- mainmenu start -->
						<div class="white menu-init" id="main-menu">
							<nav id="menu-center">
								<?php
								wp_nav_menu([
									'theme_location' => 'main-menu',
									'container' => false, // Không cần container vì đã có <nav>
									'menu_class' => '', // Xóa class mặc định
									'walker' => new Bootstrap_Menu_Walker(),
								]);
								?>
							</nav>
						</div><!-- mainmenu end -->

						<div class="menu-right d-xl-none d-block centered">
							<ul class="iconright">
								<li>
									<div id="showside" class="d-none-mobile"><span
											class="ti-menu"></span></div>
									<div id="showmobile"><span class="ti-menu"></span>
									</div>
								</li>
							</ul>
						</div>

					</div>
					<!-- row end-->
				</div><!-- container end -->
			</div>
			<!-- nav -->
		</header><!-- header end -->