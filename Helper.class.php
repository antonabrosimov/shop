<?php

class Helper {

  public static function sessionStart() {
    if( !isset($_SESSION) ) {
      session_start();
    }
  }

  public static function addMessage($message) {
    Helper::sessionStart();
    $_SESSION['message'] = $message;
  }

  public static function getMessage() {
    Helper::sessionStart();
    if ( isset($_SESSION['message']) ) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']);
      return $message;
    }
  }

  public static function isMessage() {
    Helper::sessionStart();
    return isset($_SESSION['message']);
  }

  public static function addError($error) {
    Helper::sessionStart();
    $_SESSION['error'] = $error;
  }

  public static function getError() {
    Helper::sessionStart();
    if ( isset($_SESSION['error']) ) {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      return $error;
    }
  }

  public static function isError() {
    Helper::sessionStart();
    return isset($_SESSION['error']);
  }

}

// OBICNA METODA
// $helper = new Helper();
// $helper->sessionStart();


// STATICKA METODA (ne sme da koristi $this)
// Helper::sessionStart();