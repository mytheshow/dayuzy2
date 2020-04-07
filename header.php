<!DOCTYPE HTML>
<html>
<head>
	<!--meta的常用语法https://www.jianshu.com/p/602e0a469255-->
	<meta charset="UTF-8">
	<!--告诉IE浏览器，IE8/9及以后的版本都会以最高版本IE来渲染页面。 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<!-- apple-mobile-web-app-capable 删除默认的苹果工具栏和菜单栏：设置为 yes，Web 应用会以全屏模式运行 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<!--控制状态栏显示样式-->
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!--禁止百度转码 -->
	<meta http-equiv="cache-control" content="no-siteapp">
	<link rel="shortcut icon" href="<?php echo _hui_img( 'web_favicon' )?>">
	<title><?php echo _title() ?></title>
	<?php wp_head(); ?>
  <!--让IE9以下的浏览器支持HTML5元素方法-->
  <!--[if lt IE 9]><script src="<?php echo get_stylesheet_directory_uri() ?>/js/html5.js"></script><![endif]-->
</head>
<body <?php  body_class(_bodyclass())  ?> >
<header class="header">
  <div class="container_header">
	  <?php //var_dump(esc_url(home_url('/'),['https','http2']));?>
    <h1 class="logo"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><img src="<?php echo _hui_img('header_logo'); ?>"></a></h1>
  </div>
</header>
</body>
</html>
