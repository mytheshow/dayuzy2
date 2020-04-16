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
    <h1 class="logo"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><img src="<?php echo _hui_img('header_logo'); ?>"></a></h1>
	  <?php if( is_user_logged_in() ){global $current_user; ?>
      <div class="wel">
	      <?php if (vip_type() == 0) { ?>
          <div class="wel-item"><a href="<?php echo home_url('/user?action=vip') ?>"><i class="iconfont">&#xe63f;</i> 开通VIP</a></div>
	      <?php }else{ ?>
          <div class="wel-item"><a href="<?php echo home_url('/user?action=vip') ?>" style=" color: #fadb30; "><i class="iconfont">&#xe63f;</i> <?php echo vip_type_name() ?></a></div>
	      <?php } ?>
        <div class="wel-item"><a href="javascript:;" id="search"><i class="iconfont">&#xe67a;</i></a></div>
        <div class="wel-item has-sub-menu">
          <a href="<?php echo home_url('/user') ?>">
			      <?php echo _get_user_avatar( $current_user->user_email, true, 50); ?><span class="wel-name"><?php echo $current_user->display_name ?></span>
          </a>
          <div class="sub-menu">
            <ul>
	            <?php if( $current_user->roles[0] == 'administrator'|| $current_user->roles[0] == 'editor') { ?>
                <li><a target="_blank" href="<?php echo home_url('/wp-admin/index.php') ?>">后台管理</a></li>
	            <?php } ?>
              <li><a href="<?php echo home_url('/user?action=order') ?>">我的订单</a></li>
              <li><a href="<?php echo home_url('/user?action=vip') ?>">会员特权</a></li>
              <li><a href="<?php echo home_url('/user?action=info') ?>">修改资料</a></li>
              <li><a href="<?php echo wp_logout_url(home_url()); ?>">退出</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="m-wel">
        <header>
				  <?php echo _get_user_avatar( $current_user->user_email, true, 50); ?><h4><?php echo $current_user->display_name ?></h4>
          <h5><?php echo $current_user->user_email ?></h5>
        </header>
        <div class="m-wel-content">
          <ul>
            <li><a href="<?php echo home_url('/user?action=order') ?>">我的订单</a></li>
            <li><a href="<?php echo home_url('/user?action=vip') ?>">会员特权</a></li>
            <li><a href="<?php echo home_url('/user?action=info') ?>">修改资料</a></li>
          </ul>
        </div>
        <footer>
          <a href="<?php echo wp_logout_url(home_url()); ?>">退出当前账户</a>
        </footer>
      </div>
	  <?php }?>
  </div>
  <?php

  ?>
</header>
</body>
</html>
