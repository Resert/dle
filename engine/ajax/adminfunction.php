<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004,2012 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: adminfunction.php
-----------------------------------------------------
 Назначение: Выполнение различных функций админпанели
=====================================================
*/

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define('DATALIFEENGINE', true);
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -12 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR.'/data/config.php';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/adminfunction.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';

dle_session();
$_TIME = time () + ($config['date_adjust'] * 60);

require_once ENGINE_DIR.'/modules/sitelogin.php';

//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

if( !$user_group[$member_id['user_group']]['allow_admin'] ) { die ("error"); }

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = trim(totranslit( $_COOKIE['selected_language'], false, false ));

	if ($_COOKIE['selected_language'] != "" AND @is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}
if ( file_exists( ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng' ) ) {
	require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';
} else die("Language file not found");

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
$buffer = "";

@header("Content-type: text/html; charset=".$config['charset']);

if ($_REQUEST['action'] == "clearcache") {

	if ( $member_id['user_group'] != 1 ) die ("error");

	$fdir = opendir( ENGINE_DIR . '/cache/system/' );
	while ( $file = readdir( $fdir ) ) {
		if( $file != '.' and $file != '..' and $file != '.htaccess' and $file != 'cron.php' ) {
			@unlink( ENGINE_DIR . '/cache/system/' . $file );
		
		}
	}
	
	clear_cache();

	$buffer = "<font color=\"green\">".$lang['clear_cache']."</font>";

}


if ($_REQUEST['action'] == "clearsubscribe") {

	if ( $member_id['user_group'] != 1 ) die ("error");

	$db->query("TRUNCATE TABLE " . PREFIX . "_subscribe");

	$buffer = "<font color=\"green\">".$lang['clear_subscribe']."</font>";

}

if ($_REQUEST['action'] == "sendnotice") {

	$row = $db->super_query( "SELECT id FROM " . PREFIX . "_notice WHERE user_id = '{$member_id['user_id']}'" );
	
	$notice = $db->safesql( convert_unicode($_POST['notice'], $config['charset']) );
	
	if( $row['id'] ) {
		
		$db->query( "UPDATE " . PREFIX . "_notice SET notice='{$notice}' WHERE user_id = '{$member_id['user_id']}'" );
	
	} else {
		
		$db->query( "INSERT INTO " . PREFIX . "_notice (user_id, notice) values ('{$member_id['user_id']}', '$notice')" );
	
	}

	$buffer = "<font color=\"green\">".$lang['saved']."</font>";

}

if ($_REQUEST['action'] == "deletemodules") {

	if ( $member_id['user_group'] != 1 ) die ("error");

	$id = intval($_REQUEST['id']);

	if ( $id ) {
		$db->query( "DELETE FROM " . PREFIX . "_admin_sections WHERE id = '{$id}'" );
	
		$buffer = 'ok';
	}

}

if ($_REQUEST['action'] == "catsort") {

	if( !$user_group[$member_id['user_group']]['admin_categories'] ) die ("error");

	if ( !count($_POST['list']) ) die ("error");

	$i= 0;

	foreach ( $_POST['list'] as $id => $parentid ) {
		$i++;

		$id = intval($id);
		$parentid = intval($parentid);

		if ( $id ) {

			$db->query( "UPDATE " . PREFIX . "_category SET parentid='{$parentid}', posi='{$i}' WHERE id = '{$id}'" );

		}
	}

	@unlink( ENGINE_DIR . '/cache/system/category.php' );
	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '11', '')" );

	$buffer = 'ok';

}

echo $buffer;

?>