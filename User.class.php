<?php

class User {
  private $db;
  public $id;
  public $name;
  public $email;
  public $password;
  public $new_password;
  public $password_repeat;
  public $acc_type;
  public $created_at;
  public $updated_at;
  public $deleted_at;

  function __construct($id = null) {
    $this->db = require './db.inc.php';

    if ($id) {
      $this->id = $id;
      $this->loadFromDatabase();
    }
  }

  public function loadFromDatabase() {
    $stmt_getUser = $this->db->prepare("
      SELECT *
      FROM `users`
      WHERE `id` = :id
    ");
    $stmt_getUser->execute([
      ':id' => $this->id
    ]);
    $user = $stmt_getUser->fetch();
    if ($user) {
      $this->id = $user->id;
      $this->name = $user->name;
      $this->email = $user->email;
      $this->password = $user->password;
      $this->acc_type = $user->acc_type;
      $this->created_at = $user->created_at;
      $this->updated_at = $user->updated_at;
      $this->deleted_at = $user->deleted_at;
      return true;
    } else {
      return false;
    }
  }

  public function save() {
    if ($this->id) {
      return $this->update();
    } else {
      return $this->insert();
    }
  }

  public function insert() {

    if( !$this->validateEmail() ) {
      return false;
    }

    if( !$this->validatePassword() ) {
      return false;
    }

    $stmt_insertUser = $this->db->prepare("
      INSERT INTO `users`
      (`name`, `email`, `password`)
      VALUES
      (:name, :email, :password)
    ");
    $result = $stmt_insertUser->execute([
      ':name' => $this->name,
      ':email' => $this->email,
      ':password' => md5($this->password)
    ]);
    if ($result) {
      $this->id = $this->db->lastInsertId();
      $this->loadFromDatabase();
      return $result;
    } else {
      return false;
    }
  }

  public function update() {

    if( !$this->validateEmail() ) {
      return false;
    }

    if( !$this->validateNewPassword() ) {
      return false;
    }

    $stmt_updateUser = $this->db->prepare("
      UPDATE `users`
      SET
        `name` = :name,
        `email` = :email,
        `password` = :password,
        `acc_type` = :acc_type
      WHERE `id` = :id
    ");

    if($this->new_password) {
      $password = md5($this->new_password);
    } else {
      $password = $this->password;
    }

    return $stmt_updateUser->execute([
      ':name' => $this->name,
      ':email' => $this->email,
      ':password' => $password,
      ':acc_type' => $this->acc_type,
      ':id' => $this->id
    ]);
  }

  public function delete() {
    $stmt_deleteUser = $this->db->prepare("
      UPDATE `users`
      SET `deleted_at` = NOW()
      WHERE `id` = :id
    ");
    return $stmt_deleteUser->execute([
      ':id' => $this->id
    ]);
  }

  public function all() {
    $stmt_getAllUsers = $this->db->prepare("
      SELECT *
      FROM `users`
      WHERE `deleted_at` IS NULL
      ORDER BY `created_at` DESC
    ");
    $stmt_getAllUsers->execute();
    return $stmt_getAllUsers->fetchAll();
  }

  public function login($email, $password) {
    $stmt_getUser = $this->db->prepare("
      SELECT *
      FROM `users`
      WHERE `email` = :email
      AND `password` = :password
    ");
    $stmt_getUser->execute([
      ':email' => $email,
      ':password' => md5($password)
    ]);
    $user = $stmt_getUser->fetch();
    
    if ( $user ) {
      require_once './Helper.class.php';
      Helper::sessionStart();
      $_SESSION['user_id'] = $user->id;
      return true;
    } else {
      return false;
    }
  }

  public static function isLoggedIn() {
    require_once './Helper.class.php';
    Helper::sessionStart();
    if ( isset($_SESSION['user_id']) ) {
      return true;
    } else {
      return false;
    }
  }

  public function getLoggedInUser() {
    require_once './Helper.class.php';
    Helper::sessionStart();
    if (isset($_SESSION['user_id'])) {
      $this->id = $_SESSION['user_id'];
      $this->loadFromDatabase();
    } else {
      return false;
    }
  }

  public function validateEmail() {
    require_once './Helper.class.php';

    $stmt_getUserByEmail = $this->db->prepare("
      SELECT *
      FROM `users`
      WHERE `email` = :email
    ");
    $stmt_getUserByEmail->execute([
      ':email' => $this->email
    ]);

    $user = $stmt_getUserByEmail->fetch();

    // If user didn't change email, old email is OK
    if( $user && User::isLoggedIn() ) {
      Helper::sessionStart();
      if( $_SESSION['user_id'] == $user->id ) {
        return true;
      }
    }

    if ($stmt_getUserByEmail->rowCount() > 0) {
      Helper::addError('Email is already taken.');
      return false;
    }

    if ($this->email == '') {
      Helper::addError('Email can not be empty.');
      return false;
    }

    return true;
  }

  public function validatePassword() {
    require_once './Helper.class.php';

    if ($this->password == '') {
      Helper::addError('Password can not be empty.');
      return false;
    }

    if ($this->password != $this->password_repeat) {
      Helper::addError('Passwords don\'t match.');
      return false;
    }

    return true;
  }

  public function validateNewPassword() {
    if (!$this->new_password) {
      return true;
    }

    require_once './Helper.class.php';

    if($this->new_password != $this->password_repeat) {
      Helper::addError('Passwords don\'t match.');
      return false;
    }

    return true;
  }

  public function getCart() {

    if (!User::isLoggedIn()) {
      return false;
    }

    require_once './Helper.class.php';
    Helper::sessionStart();
    
    $stmt_getCart = $this->db->prepare("
      SELECT
        `carts`.`id`,
        `products`.`title`,
        `products`.`price`,
        `carts`.`quantity`
      FROM `carts`, `products`
      WHERE `carts`.`product_id` = `products`.`id`
      AND `carts`.`user_id` = :user_id
    ");
    $stmt_getCart->execute([ ':user_id' => $_SESSION['user_id'] ]);
    return $stmt_getCart->fetchAll();
  }

  public function removeFromCart($id) {
    $stmt_deleteFromCart = $this->db->prepare("
      DELETE FROM `carts`
      WHERE `id` = :id
    ");
    return $stmt_deleteFromCart->execute([ ':id' => $id ]);
  }

  public function updateCart($id, $newQuantity) {
    $stmt_updateQuantity = $this->db->prepare("
      UPDATE `carts`
      SET `quantity` = :quantity
      WHERE `id` = :id
    ");
    return $stmt_updateQuantity->execute([
      ':quantity' => $newQuantity,
      ':id' => $id
    ]);
  }
}
