<?php
/**
 * [vip_type 当前会员类型]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:23:57+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [0 31 365 3600]
 */
function vip_type($users_id = '')
{
	global $current_user;
	$uid       = (!$users_id) ? $current_user->ID : $users_id;
	$vip_type  = get_user_meta($uid, 'vip_type', true);
	$vip_time  = get_user_meta($uid, 'vip_time', true);
	$timestamp = intval($vip_time) - time();
	if ($timestamp > 0) {
		return intval($vip_type);
	} else {
		return 0;
	}

}

/**
 * [vip_type_name 当前会员名称]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:24:57+0800
 * @param    string                   $users_id [description]
 * @return   [type]                             [description]
 */
function vip_type_name($users_id = '')
{
	global $current_user;
	$uid      = (!$users_id) ? $current_user->ID : $users_id;
	$vip_type = get_user_meta($uid, 'vip_type', true);
	//如果是空字符串
	if (!$vip_type) {
		return '普通用户';
	}
	$vip_time  = get_user_meta($uid, 'vip_time', true);
	$timestamp = intval($vip_time) - time();
	if ($timestamp > 0) {
		if (intval($vip_type) == 31) {
			$name = '月费会员';
		} elseif (intval($vip_type) == 365) {
			$name = '年费会员';
		} elseif (intval($vip_type) == 3600) {
			$name = '终身会员';
		}
	} else {
		$name = '普通用户';
	}
	return $name;
}
