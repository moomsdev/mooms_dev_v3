<?php

/**
 * Theme Options.
 *
 * Here, you can register Theme Options using the Carbon Fields library.
 *
 * @link    https://carbonfields.net/docs/containers-theme-options/
 *
 * @package WPEmergeCli
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

$optionsPage = Container::make('theme_options', __('Theme Options', 'gaumap'))
	->set_page_file('app-theme-options.php')
	->add_tab(__('Branding | Thương hiệu', 'gaumap'), [
		Field::make('image', 'logo' . currentLanguage(), __('Logo', 'gaumap'))->set_width(33.33),
		Field::make('image', 'footer_logo' . currentLanguage(), __('Footer Logo', 'gaumap'))->set_width(33.33),
		Field::make('image', 'hinh_anh_mac_dinh' . currentLanguage(), __('Default image | Hình ảnh mặc định', 'gaumap'))->set_width(33.33),
	])

	->add_tab(__('Contact | Liên hệ', 'gaumap'), [
		Field::make('html', 'info', __('', 'gaumap'))
			->set_html('----<i> Information | Thông tin </i>----'),
		Field::make('text', 'company' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'Company | Công ty'),
		Field::make('text', 'address' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'Address | Địa chỉ'),
		Field::make('textarea', 'googlemap' . currentLanguage(), __('', 'gaumap'))
			->set_attribute('placeholder', 'Google map'),
		Field::make('text', 'email' . currentLanguage(), __('', 'gaumap'))->set_width(33.33)
			->set_attribute('placeholder', 'Email'),
		Field::make('text', 'phone_number' . currentLanguage(), __('', 'gaumap'))->set_width(33.33)
			->set_attribute('placeholder', 'Phone number | Số điện thoại'),
		Field::make('text', 'hour_working' . currentLanguage(), __('', 'gaumap'))->set_width(33.33)
			->set_attribute('placeholder', 'Hour working | Giờ làm việc'),
		Field::make('html', 'socials', __('', 'gaumap'))
			->set_html('----<i> Socials | Mạng xã hội </i>----'),
		Field::make('text', 'facebook' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'facebook'),
		Field::make('text', 'instagram' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'instagram'),
		Field::make('text', 'tiktok' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'tiktok'),
		Field::make('text', 'youtube' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'youtube'),
	])

	->add_tab(__('Header  |  Footer', 'gaumap'), [
		Field::make('html', 'header', __('', 'gaumap'))
			->set_html('----<i> Header </i>----'),
		Field::make('text', 'header_label' . currentLanguage(), __('', 'gaumap'))->set_width(50),

		Field::make('html', 'footer', __('', 'gaumap'))
			->set_html('----<i> Footer </i>----'),
		Field::make('text', 'contact_label' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'Contact label | Nhãn liên hệ'),
		Field::make('text', 'contact_url' . currentLanguage(), __('', 'gaumap'))->set_width(50)
			->set_attribute('placeholder', 'Contact URL | Liên kết liên hệ'),
		Field::make('textarea', 'contact_message' . currentLanguage(), __('', 'gaumap'))
			->set_attribute('placeholder', 'Contact description | Mô tả liên hệ'),
	])

	->add_tab(__('Scripts', 'gaumap'), [
		Field::make('header_scripts', 'crb_header_script', __('Header Script', 'app')),
		Field::make('footer_scripts', 'crb_footer_script', __('Footer Script', 'app')),
	]);