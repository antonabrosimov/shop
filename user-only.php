<?php

require_once './User.class.php';

if ( !User::isLoggedIn() ) {
  header("Location: login.php");
  die();
}
