<?php
if ( function_exists('yoast_breadcrumb') ) :
	yoast_breadcrumb('<div id="page-breadcrumb">', '</div>');
elseif ( function_exists('rank_math_the_breadcrumbs') ) :
    rank_math_the_breadcrumbs();
endif;

