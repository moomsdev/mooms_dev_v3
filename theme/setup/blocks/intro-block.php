<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
$addLabels = array(
    'plural_name' => 'media',
    'singular_name' => 'media',
);

Block::make(__('Block Giới Thiệu', 'gaumap')) 
->add_fields([
    Field::make('separator', 'intro_spt', __('BLOCK Giới Thiệu', 'gaumap'))->set_width(70),
    //Title
    Field::make('text', 'intro_title', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Nhập tiêu đề của block'),
    //Description
    Field::make('textarea', 'intro_desc', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Nhập mô tả của block'),
    Field::make('complex', 'intro_item', __('', 'gaumap'))
        ->set_layout('tabbed-vertical')
        ->add_fields([
            Field::make('text', 'year', __('Năm', 'gaumap'))->set_width(30),
            Field::make('text', 'title', __('Tiêu đề', 'gaumap'))->set_width(70),
            Field::make('rich_text', 'description', __('Mô tả', 'gaumap')),
        ])->set_header_template('<% if (title) { %><%- title %><% } %>'),
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['intro_title']) ? esc_html($fields['intro_title']) : '';
    $desc = !empty($fields['intro_desc']) ? wp_kses_post($fields['intro_desc']) : '';
    $items = !empty($fields['intro_item']) ? $fields['intro_item'] : '';
?>
    	<!-- section home -->
		<section aria-label="section" class="s-top">
			<div class="container-fluid">
				<div class="row p-3-vh">
					<div class="col-12 text-center">
						<div class="main-content">
							<div class="heading">
								<div class="title">
                                    <?php echo $title; ?>
								</div>
							</div>
							<div class="desc m-auto">
                                <?php echo apply_filters('the_content', $desc); ?>
							</div>
						</div>
					</div>
                    <?php
                    foreach ( $items as $item ) :
                        $year = $item['year'];
                        $title = $item['title'];
                        $description = $item['description'];
                    ?>
                        <div class="col-md-4">
                            <div class="content-col white pb-md-0">
                                <div class="subheading">
                                    <?= $year; ?>
                                </div>
                                <div class="heading line">
                                    <?= $title; ?>
                                </div>
                                <div class="desc autoheight">
                                <?php echo apply_filters('the_content', $description); ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>

				</div>
			</div>
		</section>
		<!-- section home end -->
<?php
});
