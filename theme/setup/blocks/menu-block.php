<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Menu', 'gaumap'))
    ->add_fields([
        Field::make('separator', 'menu_spt', __('BLOCK MENU', 'gaumap')),
        //Title
        Field::make('text', 'menu_title', __('', 'gaumap'))
            ->set_attribute('placeholder', 'Nhập tiêu đề của block'),

        Field::make('complex', 'menu_item', __('', 'gaumap'))
            ->set_layout('tabbed-vertical')
            ->add_fields([
                Field::make('text', 'title', __('Tiêu đề', 'gaumap'))->set_width(70),
                Field::make('complex', 'images', __('', 'gaumap'))
                    ->set_layout('tabbed-horizontal')
                    ->add_fields([
                        Field::make('image', 'img', __('', 'gaumap')),
                    ]),
            ])->set_header_template('<% if (title) { %><%- title %><% } %>'),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $title = !empty($fields['menu_title']) ? esc_html($fields['menu_title']) : '';
        $items = !empty($fields['menu_item']) ? $fields['menu_item'] : '';
?>
    <!-- section menu -->
    <section aria-label="section" class="s-img s-bot" style="background-image: url('img/bgfoodicon.png');">
        <div class="container-fluid">
            <div class="row p-3-vh">

                <div class="col-12 text-center">
                    <div class="main-content">
                        <div class="heading">
                            <h2 class="title l-1">
                                <?php echo $title; ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <?php
                foreach ( $items as $item ) :
                    $title = $item['title'];
                    $images = $item['images'];
                ?>
                    <div class="col-12 text-center item-menu">
                        <div class="heading">
                            <h3 class="title l-1">
                                <?php echo $title; ?>
                            </h3>
                        </div>
                        <?php
                        foreach ( $images as $image ) :
                        ?>
                            <div class="content-menu hor">
                                <img src="<?= getImageUrlById($image['img']); ?>" alt="">
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                <?php
                endforeach;
                ?>

            </div>
        </div>
    </section>
    <!-- section menu -->
<?php
    });
