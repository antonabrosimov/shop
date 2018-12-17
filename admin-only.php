<?php

require_once './User.class.php';
require_once './Helper.class.php';

if ( !User::isLoggedIn() ) {
  header("Location: login.php");
  die();
}

$loggedInUser = new User();
$loggedInUser->getLoggedInUser();

if( $loggedInUser->acc_type != 'admin' ) {
  Helper::addError('<b>Access violation!</b> You need administrtive account to access request page.');
  header("Location: index.php");
  die();
}
