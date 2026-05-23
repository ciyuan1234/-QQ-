<?php
date_default_timezone_set('Asia/Shanghai');

// 全局数据库连接对象
$db_conn = null;

function connDB($dbConf)
{
	$conn = mysqli_connect($dbConf['host'], $dbConf['user'], $dbConf['pass'], $dbConf['name']);

	if ($conn) {
		mysqli_query($conn, "set names 'utf8mb4'");
		return $conn;
	}
	error_log("Database connection failed: " . mysqli_connect_error());
	return false;
}

function getDb()
{
	global $db_conn;
	if ($db_conn !== null) {
		return $db_conn;
	}

	$dbConf = array(
		'host' => '127.0.0.1', // 数据库服务器：端口号
		'user' => 'root',      // 数据库用户名
		'pass' => 'root',      // 数据库密码
		'name' => 'wpoy'       // 数据库名
	);
	
	$db_conn = connDB($dbConf);
	return $db_conn;
}

// 兼容性层：为 PHP 7+ 提供 mysql_* 函数支持
if (!function_exists('mysql_query')) {
    function mysql_query($query, $link = null) {
        if ($link === null) $link = getDb();
        return mysqli_query($link, $query);
    }
}

if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result) {
        return mysqli_fetch_array($result);
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link = null) {
        if ($link === null) $link = getDb();
        return mysqli_error($link);
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($database_name, $link = null) {
        if ($link === null) $link = getDb();
        return mysqli_select_db($link, $database_name);
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($unescaped_string, $link = null) {
        if ($link === null) $link = getDb();
        return mysqli_real_escape_string($link, $unescaped_string);
    }
}

function getTablePrefix(){
	return 'amazpot';
}

function getAppId(){
	return 'YOUR_APPID';
}

function getAppSecret(){
	return 'YOUR_APPSECRET';
}