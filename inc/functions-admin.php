<?php


/**
 * [rizhuti_setup theme start]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T11:17:53+0800
 * @return   [type]                   [description]
 */
if (!function_exists('rizhuti_setup')):

	function rizhuti_setup()
	{
		//这个功能让 WordPress 自动在主题 head 添加 日志和留言的 RSS feed links
		add_theme_support('automatic-feed-links');

		add_theme_support('title-tag');
		//文章支持特色图片
		add_theme_support('post-thumbnails');
		//参考我爱水煮鱼文章，按照尺寸裁剪就是b站上传视频封面那种，按照比例缩放就是youtube那种
		add_image_size('480-384.7', 480, 384, true);

		// 检测是否有qqid字段，没有添加，为qq登陆提供基础
		global $wpdb, $wppay_table_name;
		//查看$wpdb->query()的源码可知调试模式状态下如果报错了，就打印错误，并返回false
		$var = $wpdb->query("SELECT qqid FROM $wpdb->users");
		if (!$var) {
			$wpdb->query("ALTER TABLE $wpdb->users ADD qqid varchar(100)");
		}

		// 如果订单表不存在，插入订单表
		if ($wpdb->get_var("show tables like '{$wppay_table_name}'") != $wppay_table_name) {
			$wpdb->query("CREATE TABLE `" . $wpdb->prefix . "wppay_order` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) DEFAULT NULL COMMENT '用户id',
              `post_id` int(11) DEFAULT NULL COMMENT '关联文章id',
              `order_num` varchar(50) DEFAULT NULL COMMENT '本地订单号',
              `order_price` double(10,2) DEFAULT NULL COMMENT '订单价格',
              `order_type` tinyint(3) DEFAULT '0' COMMENT '订单类型；0为止；1文章；2会员',
              `pay_type` tinyint(3) DEFAULT '0' COMMENT '支付类型；0无；1支付宝；2微信',
              `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
              `pay_time` int(10) DEFAULT NULL COMMENT '支付时间',
              `pay_num` varchar(50) DEFAULT NULL COMMENT '支付订单号',
              `status` tinyint(3) DEFAULT '0' COMMENT '状态；0 未支付；1已支付；2失效',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET . " COMMENT='订单数据表';");
		}
		//更新表字段(缩略图裁剪)为1
		update_option('thumbnail_crop', 1);

		// CREATE PAGES 创建特定的文章页面，现在知道这些页面在哪了吧
		$init_pages = array(
			'pages/posts-likes.php' => array('点赞排行', 'likes'),
			'pages/tags.php'        => array('热门标签', 'tags'),
			'pages/sitemap.php'     => array('网站地图', 'sitemap'),
			'pages/login.php'       => array('登录注册', 'login'),
			'pages/user.php'        => array('用户中心', 'user'),
		);

		foreach ($init_pages as $template => $item) {

			$one_page = array(
				'post_title'  => $item[0],
				'post_name'   => $item[1],
				'post_status' => 'publish',
				'post_type'   => 'page',
				'post_author' => 1,
			);

			$one_page_check = get_page_by_title($item[0]);

			if (!isset($one_page_check->ID)) {
				$one_page_id = wp_insert_post($one_page);
				update_post_meta($one_page_id, '_wp_page_template', $template);
			}

		}
	}
endif;
//主题安装后执行rizhuti_setup钩子函数
add_action('after_setup_theme', 'rizhuti_setup');

//引入后台框架
require get_template_directory() . '/inc/csf/codestar-framework.php';
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

/* 调试模式选项保存为全局变量 */
if (_hui('display_errors')) {
	//ini_set修改配置文件，当本脚本执行完就回到脚本原来的配置
	ini_set("display_errors", "On");
	error_reporting(E_ALL);
} else {
	ini_set("display_errors", "Off");
}

// 禁用更新
if (_hui('display_wp_update')) {

	remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新

	remove_action('admin_init', '_maybe_update_plugins'); // 禁止 WordPress 更新插件

	remove_action('admin_init', '_maybe_update_themes');  // 禁止 WordPress 更新主题
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
