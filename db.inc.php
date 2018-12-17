<?php

$config = require './config.inc.php';

try {
  $db = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8", $config['db_user'], $config['db_pass']);
  
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  return $db;
} catch(PDOException $e) {
  echo "<h1>Error connecting to the database.</h1>";
  echo "<b>Message:</b><br />";
  echo $e->getMessage();
  // var_dump($e);
  die();
}
