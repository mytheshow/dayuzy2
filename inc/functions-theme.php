<?php

//获取分隔符
function _get_delimiter()
{
	return _hui('connector') ? _hui('connector') : '-';
}

/**
 * [_title SEO标题]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:24+0800
 * @return   [type]                   [description]
 */
function _title()
{

	global $paged;


	$html = '';
	$t    = trim(wp_title('', false));

	if ($t) {
		$html .= $t . _get_delimiter();
	}
	//拼接某一篇文章的分页，并不是网站底部那个分页，全局变量$page可以获取分页，get_query_var也可以获取分页
	//https://wangejiba.com/4691.html
	if (get_query_var('page')) {
		$html .= '第' . get_query_var('page') . '页' . _get_delimiter();
	}
	//拼接博客名字
	$html .= get_bloginfo('name');

	if (is_home()) {
		//如果不是第0页
		if ($paged > 1) {
			$html .= _get_delimiter() . '最新发布';
		} elseif (get_option('blogdescription')) {
			$html .= _get_delimiter() . get_option('blogdescription');
		}
	}
	//如果是分类页
	if (is_category()) {
		$cat_ID  = get_query_var('cat');
		//在term中获取seo-title
		$seo_str = get_term_meta($cat_ID, 'seo-title', true);
		//如果为空就去option中获取
		$cat_tit = ($seo_str) ? $seo_str : _get_tax_meta($cat_ID, 'title');
		if ($cat_tit) {
			$html = $cat_tit;
		}
	}

	if ($paged > 1) {
		$html .= _get_delimiter() . '第' . $paged . '页';
	}

	return $html;
}
//获取分类元数据
function _get_tax_meta($id = 0, $field = '')
{
	//获取id分类下的所有数据
	$ops = get_option("_taxonomy_meta_$id");

	if (empty($ops)) {
		return '';
	}
//返回数组
	if (empty($field)) {
		return $ops;
	}

	return isset($ops[$field]) ? $ops[$field] : '';
}


/**
 * [_bodyclass bodyclass]
 * @Author   dayu
 * @DateTime 2019-05-28T12:15:11+0800
 * @return   [type]  [根据不同的文章形式给body添加不同的样式类]
 */
function _bodyclass()
{
	$class = '';
	//如果是文章页或者单页面并且开启评论功能
	if ((is_single() || is_page()) && comments_open()) {
		$class .= ' comment-open';
	}
	//是文章形式
	if ((is_single() || is_page()) && get_post_format()) {

		$class .= ' postformat-' . get_post_format();
	}
	//是超级管理员
	if (is_super_admin()) {
		$class .= ' logged-admin';
	}
	//
	if (_hui('list_thumb_hover_action')) {
		$class .= ' list-thumb-hover-action';
	}

	if (_hui('phone_list_news')) {
		$class .= ' list-news';
	}

	return trim($class);
}

$current_theme = wp_get_theme();
//获取主题的版本号
function _the_theme_version()
{
	global $current_theme;
	return $current_theme->get('Version');
}


/**
 * [_load_scripts 加载主题静态资源]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:56+0800
 * @return   [type]                   [description]
 */
function _load_scripts()
{
	if (!is_admin()) {
		//注册style句柄 第三个参数是依赖的句柄
		wp_register_style('main', get_stylesheet_directory_uri() . '/style.css', array(), _the_theme_version(), 'all');
		//使用style句柄
		wp_enqueue_style('main');
	}
}
add_action('wp_enqueue_scripts', '_load_scripts');

function _the_theme_avatar()
{
	return get_stylesheet_directory_uri() . '/img/avatar.png';
}

/**
 * [_get_user_avatar 获取头像]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:17:13+0800
 * @param    string                   $user_email [description]
 * @param    boolean                  $src        [description]
 * @param    integer                  $size       [description]
 * @return   [type]                               [description]
 */
function _get_user_avatar($user_email = '', $src = false, $size = 50)
{
	//邮箱 大小 默认头像
	$avatar = get_avatar($user_email, $size, _the_theme_avatar());
	//如果传递了url头像
	if ($src) {
		return $avatar;
	} else {
		return str_replace(' src=', ' data-src=', $avatar);
	}

}
function get_ssl_avatar($avatar) {
   $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
   return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');


/**
 * Header_Menu_Walker类
 */
class Header_Menu_Walker extends Walker_Nav_Menu
{
	//自定义子菜单，该函数会循环执行，儿子，孙子... 一般也就循环到孙子，在往下就不好看了
	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		//制表符和换行 在右键 查看网页源代码可以看出
		$indent      = ($depth > 0 ? str_repeat("\t", $depth) : ''); // 缩进
		$classes     = array('sub-menu');
		$class_names = implode(' ', $classes); //用空格分割多个样式名
		$output .= "\n" . $indent . '<div class="' . $class_names . '"><ul>' . "\n"; //
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}</div>";
	}
}

/**
 * 移除菜单的多余CSS选择器
 * From https://www.wpdaxue.com/remove-wordpress-nav-classes.html
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
	return is_array($var) ? array_intersect($var, array('current_page_item', 'menu-item-has-children')) : '';
}

/**
 * 注册菜单
 * https://blog.csdn.net/qq_37296622/article/details/82633833
 */
if (function_exists('register_nav_menus')) {
	//键值对数组，名字随意，不必要非得是nav
	register_nav_menus(array(
		'nav' => __('导航aa'),
	));
}

/**
 * [_the_menu menu]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:28:16+0800
 * @param    string                   $location [description]
 * @return   [type]                             [description]
 * https://blog.csdn.net/qq_37296622/article/details/82633833
 */
function _the_menu($location = 'nav')
{
	// 根据位置找菜单，在输出菜单项
	//整个流程是，我先注册个为 ‘nav’ 的位置，get_nav_menu_locations()获取已经注册的菜单位置以及分配给该位置的菜单项的term_id，获取该菜单的项item
	//如果container为空就默认套个div，并且再套个ul，如果是个ul默认就套个 $menu = $args['before'] . $menu . $args['after'];不用再套div和ul了
	//如果我后台创建了两个菜单 都勾选了"导航呢？"，发现根本不会，第二个勾选导航，第一个菜单的导航就自动取消勾选了
	echo wp_nav_menu(array('theme_location' => $location, 'container' => 'ul', 'echo' => false, 'walker' => new Header_Menu_Walker()));
}

function _the_theme_name()
{
	global $current_theme;
	return $current_theme->get('Name');
}


/**
 * [timthumb 图像裁切]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:16:48+0800
 * @param    [type]                   $src  [description]
 * @param    [type]                   $size [description]
 * @param    [type]                   $set  [description]
 * @return   [type]                         [description]
 */
function timthumb($src, $size = null, $set = null)
{

	$modular = _hui('thumbnail_handle');
	//is_numeric() 函数用于检测变量是否为数字或数字字符串
	if (is_numeric($src)) {
		//WP自带裁剪
		if ($modular == 'timthumb_mi') {
			// $src = image_downsize( $src, $size['w'].'-'.$size['h'] );
			//参数1：传递图片的ID 参数2：裁剪风格，可以自定义宽高
			$src = image_downsize($src, 'thumbnail');
		} else {
			$src = image_downsize($src, 'full');
		}
		$src = $src[0];
	}

	if ($set == 'original') {
		return $src;
	}
	//默认 timthumb.php裁剪（可保持缩略图大小一致）,自定义裁剪文件
	if ($modular == 'timthumb_php' || empty($modular) || $set == 'tim') {

		return get_stylesheet_directory_uri() . '/timthumb.php?src=' . $src . '&h=' . $size["h"] . '&w=' . $size['w'] . '&zc=1&a=c&q=100&s=1';

	} else {
		return $src;
	}

}


/**
 * [_get_post_thumbnail_url 输出缩略图地址]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:16:30+0800
 * @param    [type]                   $post [post]
 * @return   [type]                         [description]
 */
function _get_post_thumbnail_url($post = null)
{
	if ($post === null) {
		global $post;
	}

	if (has_post_thumbnail($post)) {
		//如果有特色缩略图，则输出缩略图地址
		$post_thumbnail_src = get_post_thumbnail_id($post->ID);
	} else {
		$post_thumbnail_src = '';
		@$output            = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if (!empty($matches[1][0])) {

			global $wpdb;
			$att = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s'", $matches[1][0]));

			if ($att) {
				$post_thumbnail_src = $att->ID;
			} else {
				$post_thumbnail_src = $matches[1][0];
			}

		} else {

			$post_thumbnail_src = _the_theme_thumb();

		}
	}
	return $post_thumbnail_src;
}

