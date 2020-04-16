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
