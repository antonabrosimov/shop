<?php

$db = require './db.inc.php';
$config = require './config.inc.php';


/**
 * Delete old tables if url contains ?refresh
 */
if ( isset($_GET['refresh']) ) {
  $db->query("DROP TABLE `users`");
  $db->query("DROP TABLE `products`");
  $db->query("DROP TABLE `categories`");
  $db->query("DROP TABLE `comments`");
}


/**
 * Create users table
 */
$stmt_createUsersTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `users` (
    `id` int AUTO_INCREMENT,
    `name` varchar(30),
    `email` varchar(50),
    `password` varchar(32),
    `acc_type` enum('admin', 'user') DEFAULT 'user',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT now() ON UPDATE now(),
    `deleted_at` datetime DEFAULT null,
    PRIMARY KEY (`id`)
  )
");
$stmt_createUsersTable->execute();


/**
 * Insert admin account
 */
$stmt_getUsers = $db->prepare("
    SELECT *
    FROM `users`
");
$stmt_getUsers->execute();
$numberOfusers = $stmt_getUsers->rowCount();
if ( $numberOfusers <= 0 ) {
  $stmt_insertAdmin = $db->prepare("
    INSERT INTO `users`
    (`name`, `email`, `password`, `acc_type`)
    VALUES
    (:name, :email, :password, :acc_type)
  ");
  $stmt_insertAdmin->execute([
    ':name' => $config['admin_name'],
    ':email' => $config['admin_email'],
    ':password' => $config['admin_password'],
    ':acc_type' => 'admin'
  ]);
}


/**
 * Create products table
 */
$stmt_createProductsTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `products` (
    `id` int AUTO_INCREMENT,
    `cat_id` int,
    `title` varchar(255),
    `description` text,
    `img` varchar(255),
    `price` int,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT now() ON UPDATE now(),
    `deleted_at` datetime DEFAULT null,
    PRIMARY KEY (`id`)
  )
");
$stmt_createProductsTable->execute();


/**
 * Create categories table
 */
$stmt_createCategoriesTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `categories` (
      `id` int AUTO_INCREMENT,
      `title` varchar(255),
      `description` text,
      PRIMARY KEY (`id`)
    )
");
$stmt_createCategoriesTable->execute();

/**
 * Create comments table
 */
$stmt_createCommentsTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `comments` (
    `id` int AUTO_INCREMENT,
    `product_id` int,
    `user_id` int,
    `body` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT now() ON UPDATE now(),
    `deleted_at` datetime DEFAULT null,
    PRIMARY KEY (`id`)
  )
");
$stmt_createCommentsTable->execute();

/**
 * Create carts table
 */
$stmt_createCartsTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `carts` (
      `id` int AUTO_INCREMENT,
      `user_id` int,
      `product_id` int,
      `quantity` int,
      PRIMARY KEY (`id`)
    )
");
$stmt_createCartsTable->execute();

/**
 * Create likes table
 */

 $stmt_createLikesTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `likes` (
    `id` int AUTO_INCREMENT,
    `user_id` int,
    `product_id` int,
    PRIMARY KEY (`id`)
  )
 ");
 $stmt_createLikesTable->execute();


echo '<h1>Success!</h1>';

// unlink('./install.php');
