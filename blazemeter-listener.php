<?php
global $blazemeter_api;

$user_key = trim((string) @$_REQUEST['userKey']);

if(!$user_key)
    return;

echo '<div style="font-family: sans-serif; font-size: 12px; line-height: 1.4em; color: white;">Loading&hellip; Please wait.</div>';

$blazemeter_api->ajax_login();
?>
