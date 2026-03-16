<?php

error_reporting(E_ALL & ~E_NOTICE);

define('SITE_URL', 'http://localhost/dev/mykakugen/web/');
define('SERVICE_NAME', '格言リマインダー マイカクゲン');
define('SERVICE_SHORT_NAME', 'マイカクゲン');
define('COPYRIGHT', '&copy; 2013 SenseShare');
define('ADMIN_MAIL_ADDRESS', 'halzion00@gmail.com');

define('DB_HOST', 'localhost');
define('DB_NAME', 'mykakugen');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');

$delivery_hours_array = array(
		"99" => "しない",
		"0"   => "0時",
		"1"   => "1時",
		"2"   => "2時",
		"3"   => "3時",
		"4"   => "4時",
		"5"   => "5時",
		"6"   => "6時",
		"7"   => "7時",
		"8"   => "8時",
		"9"   => "9時",
		"10" => "10時",
		"11" => "11時",
		"12" => "12時",
		"13" => "13時",
		"14" => "14時",
		"15" => "15時",
		"16" => "16時",
		"17" => "17時",
		"18" => "18時",
		"19" => "19時",
		"20" => "20時",
		"21" => "21時",
		"22" => "22時",
		"23" => "23時",
);
