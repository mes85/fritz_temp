#!/usr/bin/php
<?php

include 'passwords.inc.php';

$config = (isset($argv[1]) && $argv[1] == 'config') ? true : false;

$tmp = simplexml_load_string(file_get_contents($fritz_url));

if ($tmp->BlockTime > 0) {
	trigger_error('BlockTime was set', E_USER_ERROR);
}

$challenge = $tmp->Challenge;

$challenge_str = $challenge . '-' . $fritz_pwd;
$md_str = md5(iconv("UTF-8", "UTF-16LE", $challenge_str));
$response = $challenge . '-' . $md_str;
$tmp = simplexml_load_string(file_get_contents($fritz_url . '?user=' . $fritz_user . '&response=' . $response));

$sid = $tmp->SID;

$tmp = simplexml_load_string(file_get_contents($auto_url . '?switchcmd=getdevicelistinfos&sid=' . $sid));

if ($config) {
	echo 'graph_title Temperatures' . PHP_EOL;
	echo 'graph_vlabel degrees Celsius' . PHP_EOL;
}

foreach ($tmp->device as $device) {
	if (!isset($device->temperature)) continue;
	if ($config) {
		echo preg_replace('/[^a-zA-Z]/', '', $device->name);
		echo '.label ';
		echo preg_replace(array('/ß/', '/ä/', '/ö/', '/ü/'), array('ss', 'ae', 'oe', 'ue'), $device->name);
	} else {
		if ($device->present != 1) continue;
		echo preg_replace('/[^a-zA-Z]/', '', $device->name);
		echo '.value ';
		echo ( $device->temperature->celsius / 10);
	}
	echo PHP_EOL;
}

$tmp = simplexml_load_string(file_get_contents($fritz_url . '?logout=1&sid=' . $sid));

?>
