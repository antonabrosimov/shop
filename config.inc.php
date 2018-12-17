<?php

$config = [];

$config['db_host'] = 'localhost';
$config['db_name'] = 'prodavnica_db';
$config['db_user'] = 'root';
$config['db_pass'] = '';
$config['admin_name'] = "Admin";
$config['admin_email'] = 'bata@gmail.com';
$config['admin_password'] = md5('12345');
$config['products_per_page'] = 6;

return $config;
