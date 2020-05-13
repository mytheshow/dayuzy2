<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'cs_my_options';

defined('CS_OPTION') or define('CS_OPTION', $prefix);

//
// Create options
//
CSF::createOptions($prefix, array(
	'menu_title' => '主题设置',
	'menu_slug'  => 'cs-options',
));


//
// Create a 基本设置
//
CSF::createSection($prefix, array(
	'title'  => '基本设置',
	'icon'   => 'fa fa-rocket',
	'fields' => array(

	),
));
