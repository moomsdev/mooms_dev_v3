<?php

namespace App\PostTypes;

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field;
use gaumap\Abstracts\AbstractPostType;

class blog extends \App\Abstracts\AbstractPostType
{

    public function __construct() {
        $this->showThumbnailOnList = true;
        $this->supports            = [
            'title',
            'editor',
            'thumbnail',
            'page-attributes',
        ];

        $this->menuIcon         = 'dashicons-share'; //https://developer.wordpress.org/resource/dashicons/
        $this->post_type        = 'blog';
        $this->singularName     = $this->pluralName = __('Blog', 'gaumap'); 
        $this->titlePlaceHolder = __('Post', 'gaumap');
        $this->slug             = 'blogs';
        parent::__construct();
    }
}
