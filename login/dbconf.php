<?php
//DATABASE CONNECTION VARIABLES
$host = 'mysql.lodge104.net'; // Host name
$username = 'lodge104_app06'; // Mysql username
$password = 'much6WOT_noos'; // Mysql password
$db_name = 'lodge104_app06_user'; // Database name

$tbl_prefix = ''; //Prefix for all database tables
$tbl_members = $tbl_prefix.'members';
$tbl_memberinfo = $tbl_prefix.'member_info';
$tbl_roles = $tbl_prefix.'roles';
$tbl_member_roles = $tbl_prefix.'member_roles';
$tbl_attempts = $tbl_prefix.'login_attempts';
$tbl_deleted = $tbl_prefix.'deleted_members';
$tbl_tokens = $tbl_prefix.'tokens';
$tbl_cookies = $tbl_prefix.'cookies';
$tbl_app_config = $tbl_prefix.'app_config';
$tbl_mail_log = $tbl_prefix.'mail_log';
$tbl_member_jail = $tbl_prefix.'member_jail';
$tbl_permissions = $tbl_prefix.'permissions';
$tbl_role_permissions = $tbl_prefix.'role_permissions';