<?php

require get_template_directory() . '/inc/csf/dayuzy/options.dayuzy.php';

if ( ! function_exists( '_hui' ) ) {
	function _hui( $option = '', $default = null ) {
		$options = get_option(CS_OPTION); // Attention: Set your unique id of the framework
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}

if (!function_exists('_hui_img')) {
	function _hui_img($option = '', $default = '')
	{
		$options = get_option(CS_OPTION);
		return ( isset( $options[$option] ) ) ? $options[$option]['url'] : $default;
	}
}

/**
 * 日进去主题的模板标签函数
 */
require_once get_stylesheet_directory() . '/inc/functions-theme.php';
/**
 *
 * 日进去主题的商城订单框架
 */
require_once get_stylesheet_directory() . '/shop/init.php';
